<?php

namespace App\Services;

use App\Abstracts\AbstractStateService;
use App\Models\Chapter;
use App\Models\State;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class OregonLawService extends AbstractStateService
{
    public function __construct(?string $title)
    {
        $this->state = State::where('name', 'oregon')->first();
        $this->endpoint = 'https://www.oregonlegislature.gov/bills_laws/';
        $this->title = $title ?? '30';
    }

    public function saveChapters(): array
    {
        $content = $this->fetch($this->endpoint.'pages/ors.aspx');

        $chapterCount = 0;
        $initialCount = $this->state->chapters->count();

        $titleTable = $content->filter('table')->eq(1);
        if ($titleTable->count() > 0) {
            $titleTable->filter('tbody')->each(function (Crawler $node) use (&$chapterCount) {
                if (str_contains($node->text(), 'Title Number : '.$this->title)) {
                    // get first and last chapters from the table text
                    $chapters = explode('-', Str::between($node->html(), 'Chapters ', '<span'));

                    // first chapter has the list of all chapters in the html
                    $page = $this->fetch($this->endpoint.'ors/ors'.$chapters[0].'.html');

                    $page->filter('p span')->each(function (Crawler $chapterNode) use (&$chapterCount) {
                        $content = htmlentities($chapterNode->text(), null, 'utf-8');

                        // locate each chapter on the page based on how many spaces are before it...????
                        $spacesBeforeChapterNumber = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                        if (Str::startsWith($content, ['Chapter', $spacesBeforeChapterNumber])) {
                            $chapterString = trim(Str::remove(['Chapter', '&nbsp;'], $content));
                            $chapterArray = explode('. ', $chapterString);
                            if (2 === count($chapterArray)) {
                                $this->saveChapter($chapterArray[0], $chapterArray[1]);
                                $chapterCount++;
                            }
                        }
                    });
                }
            });
        }

        return $this->response($initialCount, $chapterCount, 'chapters');
    }

    public function saveChapterSections(): array
    {
        $initialCount = 0;

        // this value is updated by reference in the loop below.
        $sectionCount = 0;

        $activeChapters = $this->state->chapters()->whereActive('1')->get();

        foreach ($activeChapters as $chapter) {
            $initialCount += $chapter->sections->count();

            $urlString = $this->endpoint.'ors/ors'.$chapter->code.'.html';
            $chapterPage = $this->fetch($urlString);

            $chapterPage->filter('p span')->each(function (Crawler $node) use ($chapter, $urlString, &$sectionCount) {
                $content = htmlentities($node->text(), null, 'utf-8');

                if (Str::startsWith($content, $chapter->code)) {
                    // literally using the number of forced spaces to explode this data.
                    $numberOfSpaces = $chapter->code === '329A' ? '&nbsp;' : '&nbsp;&nbsp;&nbsp;&nbsp;';
                    $sectionArray = explode($numberOfSpaces, $content);
                    if (count($sectionArray) === 2) {
                        $this->saveSection($chapter, [
                            'code' => $sectionArray[0],
                            'description' => $sectionArray[1],
                            'url' => $urlString,
                        ]);

                        $sectionCount++;
                    }
                }
            });
        }

        return $this->response($initialCount, $sectionCount, 'sections');
    }

    public function saveSectionContent(): array
    {
        $contentCount = 0;
        $sections = $this->state->sections;

        foreach ($sections as $section) {
            $sectionPage = $this->fetch($section->url);

            // we need to find the p tag with the section code, then append further p tag contents until the section code changes.
            $hasTitle = false;

            // these values are updated by reference in the loop below.
            $sectionCode = '';
            $contents = '';

            $sectionPage->filter('p')->each(function (Crawler $node) use ($section, &$hasTitle, &$sectionCode, &$contents, &$contentCount) {
                $hasTitle = $node->filter('b')->count();
                if ($hasTitle) {
                    $sectionCode = $node->filter('b')->text('');
                }
                // as long as the section code doesn't change, keep appending contents
                if (Str::contains($sectionCode, $section->code)) {
                    if ($hasTitle) {
                        $contents .= htmlentities($node->filter('span')->eq(1)->text(''), null, 'utf-8');
                    } else {
                        $contents .= htmlentities($node->text(''), null, 'utf-8');
                    }
                }
            });
            $sectionContents = html_entity_decode(Str::replace('&nbsp;', '', $contents));

            if ($sectionContents !== $section->content) {
                $section->update(['content' => $sectionContents]);
            }

            if (! empty($sectionContents)) {
                $contentCount++;
            }
        }

        return $this->response($sections->count(), $contentCount, 'contents');
    }
}

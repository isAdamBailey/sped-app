<?php

namespace App\Services;

use App\Abstracts\AbstractLawService;
use App\Models\Section;
use App\Models\State;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class IDEAService extends AbstractLawService
{
    public function __construct(?string $title)
    {
        $this->state = State::where([
            'name' => 'federal',
            'code_title' => 'IDEA',
        ])->first();

        $this->endpoint = 'https://sites.ed.gov/idea/';
        $this->title = $title ?? 'statute-chapter-33';
    }

    public function saveChapters(): array
    {
//        $subchapters = ['subchapter-i', 'subchapter-ii', 'subchapter-ii', 'subchapter-iv'];

        $chapterCount = 0;
        $initialCount = $this->state->chapters->count();

        $content = $this->fetch($this->endpoint.$this->title);
        $content->filter('h4.analysis-subhead')->each(function (Crawler $node) use (&$chapterCount) {
            $code = trim(Str::lower(Str::before($node->text(''), '(')));
            // turn the chapter name into a format we can fetch in the url
            $formattedCode = Str::replace(' ', '-', $code);
            if (! empty($formattedCode)) {
                $description = Str::between($node->text(''), ')', '(');

                $this->saveChapter($formattedCode, preg_replace("/\W|_/", ' ', $description));

                $chapterCount++;
            }
        });

        return $this->response($initialCount, $chapterCount, 'chapters');
    }

    public function saveChapterSections(): array
    {
        $initialCount = 0;

        // this value is updated by reference in the loop below.
        $sectionCount = 0;

        DB::table('chapters')->where([
            'state_id' => $this->state->id,
            'active' => 1,
        ])->chunkById(100, function ($chapters) use ($initialCount, &$sectionCount) {
            foreach ($chapters as $chapter) {
                $initialCount += Section::where('chapter_id', $chapter->id)->count();

                $chapterPage = $this->fetch($this->endpoint.$this->title.'/'.$chapter->code);

                $chapterPage->filter('h3.section-head')->each(function (Crawler $node) use (&$sectionCount, $chapter) {
                    $sectionCode = preg_replace("/\W|_/", '', Str::before($node->text(''), '.'));

                    if (! empty($sectionCode)) {
                        $description = Str::after($node->text(''), '.');

                        $this->saveSection($chapter, [
                            'code' => $sectionCode,
                            'description' => $description,
                            'url' => $node->filter('a')->link()->getUri(),
                        ]);

                        $sectionCount++;
                    }
                });
            }
        });

        return $this->response($initialCount, $sectionCount, 'sections');
    }

    public function saveSectionContent(): array
    {
        $contentCount = 0;
        $sectionCount = Section::where('state_id', $this->state->id)->count();

        DB::table('sections')
            ->where('state_id', $this->state->id)
            ->chunkById(100, function ($sections) use (&$contentCount) {
                foreach ($sections as $section) {
                    $sectionPage = $this->fetch($section->url);

                    // try to strip out any references to the code at the beginning to remove
                    // headers and breadcrumbs
                    $sectionContent = $sectionPage->filter('div.entry-content')->text('');
                    $strippedContent = Str::after($sectionContent, $section->code.'.');

                    if ($strippedContent !== $section->content) {
                        Section::where('id', $section->id)->update(['content' => $sectionContent]);
                    }

                    if (! empty($strippedContent)) {
                        $contentCount++;
                    }
                }
            });

        return $this->response($sectionCount, $contentCount, 'contents');
    }
}

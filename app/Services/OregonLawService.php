<?php

namespace App\Services;

use App\Abstracts\AbstractStateService;
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

        $titleTable = $content->filter('table')->eq(1);
        if ($titleTable->count() > 0) {
            $titleTable->filter('tbody')->each(function (Crawler $node) use (&$chapterCount) {
                if (str_contains($node->text(), 'Title Number : '.$this->title)) {
                    // get array of chapters from the table text
                    $chapters = explode('-', Str::between($node->html(), 'Chapters ', '<span'));

                    // first chapter has the list of all chapters in the html
                    $page = $this->fetch($this->endpoint.'ors/ors'.$chapters[0].'.html');


                    dd($page);

                }
//                $code = $node->filter('td a')->first()->text('');
//                if (!empty($code)) {
//                    $description = $node->filter('td')->last()->text('No Description');
//
//                    $this->saveChapter($code, $description);
//
//                    ++$chapterCount;
//                }
            });
        }

        return $this->response($chapterCount, 'chapters');
    }

    public function saveChapterSections(): array
    {
        $sectionCount = 0;

        return $this->response($sectionCount, 'sections');
    }

    public function saveSectionContent(): array
    {
        $contentCount = 0;

        return $this->response($contentCount, 'contents');
    }
}
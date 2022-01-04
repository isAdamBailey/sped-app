<?php

namespace App\Services;

use App\Abstracts\AbstractStateService;
use App\Models\State;
use Symfony\Component\DomCrawler\Crawler;

class WashingtonLawService extends AbstractStateService
{
    public function __construct(?string $title)
    {
        $this->state = State::where('name', 'washington')->first();
        $this->endpoint = 'https://app.leg.wa.gov/RCW/default.aspx?cite=';
        $this->title = $title ?? '28A';
    }

    public function saveChapters(): array
    {
        $content = $this->fetch($this->endpoint.$this->title);

        $chapterCount = 0;

        $chapterTable = $content->filter('table')->first();
        if ($chapterTable->count() > 0) {
            $chapterTable->filter('tr')->each(function (Crawler $node) use (&$chapterCount) {
                $code = $node->filter('td a')->first()->text('');
                if (!empty($code)) {
                    $description = $node->filter('td')->last()->text('No Description');

                    $this->saveChapter($code, $description);

                    ++$chapterCount;
                }
            });
        }

        return $this->response($chapterCount, 'chapters');
    }

    public function saveChapterSections(): array
    {
        $sectionCount = 0;

        $activeChapters = $this->state->chapters()->whereActive('1')->get();
        foreach ($activeChapters as $chapter) {
            $chapterPage = $this->fetch($this->endpoint.$chapter->code);

            $sectionTable = $chapterPage->filter('table')->first();
            if ($sectionTable->count() > 0) {
                $sectionTable->filter('tr')->each(function (Crawler $node) use (&$sectionCount, $chapter) {
                    $sectionCode = $node->filter('td a')->text('');
                    if (!empty($sectionCode)) {
                        $description = $node->filter('td')->last()->text('No Description');

                        $this->saveSection($chapter, $sectionCode, $description);
                        ++$sectionCount;
                    }
                });
            }
        }

        return $this->response($sectionCount, 'sections');
    }

    public function saveSectionContent(): array
    {
        $contentCount = 0;

        foreach ($this->state->sections as $section) {
            $sectionPage = $this->fetch($section->url);

            $sectionContent = $sectionPage->filter('#contentWrapper div')->eq(2);
            $section->update(['content' => $sectionContent->text('')]);

            ++$contentCount;
        }

        return $this->response($contentCount, 'contents');
    }
}

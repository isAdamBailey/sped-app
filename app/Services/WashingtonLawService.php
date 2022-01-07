<?php

namespace App\Services;

use App\Abstracts\AbstractStateService;
use App\Models\Chapter;
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
        $initialCount = Chapter::count();

        $chapterTable = $content->filter('table')->first();
        if ($chapterTable->count() > 0) {
            $chapterTable->filter('tr')->each(function (Crawler $node) use (&$chapterCount) {
                $code = $node->filter('td a')->first()->text('');
                if (! empty($code)) {
                    $description = $node->filter('td')->last()->text('No Description');

                    $this->saveChapter($code, $description);

                    $chapterCount++;
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

            $chapterPage = $this->fetch($this->endpoint.$chapter->code);

            $sectionTable = $chapterPage->filter('table')->first();

            if ($sectionTable->count() > 0) {
                $sectionTable->filter('tr')->each(function (Crawler $node) use (&$sectionCount, $chapter) {
                    $sectionCode = $node->filter('td a')->text('');

                    if (! empty($sectionCode)) {
                        $description = $node->filter('td')->last()->text('');

                        $this->saveSection($chapter, [
                            'code' => $sectionCode,
                            'description' => $description,
                            'url' => $this->endpoint.$sectionCode,
                        ]);

                        $sectionCount++;
                    }
                });
            }
        }

        return $this->response($initialCount, $sectionCount, 'sections');
    }

    public function saveSectionContent(): array
    {
        $contentCount = 0;
        $sections = $this->state->sections;

        foreach ($sections as $section) {
            $sectionPage = $this->fetch($section->url);

            $sectionContent = $sectionPage->filter('#contentWrapper div')->eq(2)->text('');

            if ($sectionContent !== $section->content) {
                $section->update(['content' => $sectionContent]);
            }

            if (! empty($sectionContent)) {
                $contentCount++;
            }
        }

        return $this->response($sections->count(), $contentCount, 'contents');
    }
}

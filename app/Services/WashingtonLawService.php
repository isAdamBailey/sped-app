<?php

namespace App\Services;

use App\Abstracts\AbstractLawService;
use App\Models\Section;
use App\Models\State;
use Illuminate\Support\Facades\DB;
use Symfony\Component\DomCrawler\Crawler;

class WashingtonLawService extends AbstractLawService
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
        $initialCount = $this->state->chapters->count();

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

        DB::table('chapters')->where([
            'state_id' => $this->state->id,
            'active' => 1,
        ])->chunkById(100, function ($chapters) use ($initialCount, &$sectionCount) {
            foreach ($chapters as $chapter) {
                $initialCount += Section::where('chapter_id', $chapter->id)->count();

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

                    $sectionContent = $sectionPage->filter('#contentWrapper div')->eq(2)->text('');

                    if ($sectionContent !== $section->content) {
                        Section::where('id', $section->id)->update(['content' => $sectionContent]);
                    }

                    if (! empty($sectionContent)) {
                        $contentCount++;
                    }
                }
            });

        return $this->response($sectionCount, $contentCount, 'contents');
    }
}

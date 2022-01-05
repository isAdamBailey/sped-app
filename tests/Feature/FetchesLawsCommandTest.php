<?php

namespace Tests\Feature;

use App\Models\Section;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FetchesLawsCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_fetches_washington_chapters_and_contents()
    {
        $this->markTestSkipped('it takes a long time');
        // title 1 has 10 chapters totalling 143 sections
        $this->helperTestChapterContents('washington', [
            'title' => 1,
            'chapterCount' => 10,
            'sectionCount' => 143,
        ]);
    }

    public function test_fetches_oregon_chapters_and_contents()
    {
        $this->markTestSkipped('it takes a long time');
        // oregon title 30 has 28 chapters totalling 1753 sections
        $this->helperTestChapterContents('oregon', [
            'title' => 30,
            'chapterCount' => 28,
            'sectionCount' => 1753,
        ]);
    }

    private function helperTestChapterContents(string $state, array $data)
    {
        $this->artisan('fetch:laws '.$state.' '.$data['title'])
            ->expectsOutput('chapters were imported')
            ->expectsOutput('sections were imported')
            ->expectsOutput('contents were imported')
            ->assertExitCode(0);

        $this->assertDatabaseCount('chapters', $data['chapterCount']);
        $this->assertDatabaseCount('sections', $data['sectionCount']);
        // make sure each section has content
        $this->assertFalse(Section::where('content', '')->exists());
        $this->assertFalse(Section::whereNull('content')->exists());
    }
}

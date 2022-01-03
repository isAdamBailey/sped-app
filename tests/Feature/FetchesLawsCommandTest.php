<?php

namespace Tests\Feature;

use App\Models\Section;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FetchesLawsCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_fetches_washington_chapters()
    {
        // title 28a has 73 chapters totalling 1428 sections (actual title we want)
//        $title = '28A';
//        $chapterCount = 73;
//        $sectionCount = 1428;

        // title 1 has 10 chapters totalling 143 sections (quicker for testing)
        $title = 1;
        $chapterCount = 10;
        $sectionCount = 143;

        $this->artisan('fetch:laws washington '.$title)
            ->expectsOutput('chapters were imported')
            ->expectsOutput('sections were imported')
            ->expectsOutput('contents were imported')
            ->assertExitCode(0);

        $this->assertDatabaseCount('chapters', $chapterCount);
        $this->assertDatabaseCount('sections', $sectionCount);
        // make sure each section has content
        $this->assertFalse(Section::where('content', '')->exists());
        $this->assertFalse(Section::whereNull('content')->exists());
    }
}

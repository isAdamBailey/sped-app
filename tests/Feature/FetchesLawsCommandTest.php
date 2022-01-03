<?php

namespace Tests\Feature;

use App\Models\Chapter;
use App\Models\Section;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FetchesLawsCommandTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fetches_washington_chapters()
    {
        // washington title 28a has 73 chapters totalling 1432 sections
//        $title = '28A';
//        $chapterCount = 73;
//        $sectionCount = 1432;

        // washington title 1 has 10 chapters totalling 143 sections
        $title = 1;
        $chapterCount = 10;
        $sectionCount = 143;

        $this->artisan('fetch:laws washington '.$title)
            ->expectsOutput($chapterCount.' chapters were imported')
            ->expectsOutput($sectionCount.' sections were imported')
            ->assertExitCode(0);

        $this->assertDatabaseCount('chapters', $chapterCount);
        $this->assertDatabaseCount('sections', $sectionCount);
    }
}

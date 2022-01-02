<?php

namespace Tests\Feature;

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
    public function test_saves_chapters()
    {
        // title 28a has 73 chapters
        $this->artisan('fetch:state-laws')
            ->expectsOutput('73 chapters have been imported')
            ->assertExitCode(0);
    }
}

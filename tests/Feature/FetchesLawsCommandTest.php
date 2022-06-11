<?php

namespace Tests\Feature;

use App\Mail\AdminEmail;
use App\Models\Section;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class FetchesLawsCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_unexpected_response_is_emailed_to_admins()
    {
        Mail::fake();
        $users = User::factory()->count(5)->withPersonalTeam()->create();
        foreach ($users as $user) {
            $user->givePermissionTo('edit site settings');
        }

        $this->artisan('fetch:laws poop')
            ->expectsOutput('Enter a valid state');

        Mail::assertSent(AdminEmail::class, 5);
    }

    public function test_fetches_washington_chapters_and_contents()
    {
//        $this->markTestSkipped('it takes a long time');
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
        // oregon title 30 has 28 chapters totalling 1784 sections
        $this->helperTestChapterContents('oregon', [
            'title' => 30,
            'chapterCount' => 28,
            'sectionCount' => 1784,
        ]);
    }

    public function test_fetches_IDEA_chapters_and_contents()
    {
        $this->markTestSkipped('it takes a long time');
        // IDEA has 4 chapters totalling 34 sections
        $this->helperTestChapterContents('idea', [
            'title' => 'statute-chapter-33',
            'chapterCount' => 4,
            'sectionCount' => 34,
        ]);
    }

    private function helperTestChapterContents(string $state, array $data)
    {
        $this->artisan('fetch:laws '.$state.' '.$data['title'])
            ->expectsOutput('We had 0 chapters stored, but found '.$data['chapterCount'].'. '.$data['chapterCount'].' chapters were imported')
            ->expectsOutput('We had 0 sections stored, but found '.$data['sectionCount'].'. '.$data['sectionCount'].' sections were imported')
            ->expectsOutput($data['sectionCount'].' contents were imported')
            ->assertExitCode(0);

        $this->assertDatabaseCount('chapters', $data['chapterCount']);
        $this->assertDatabaseCount('sections', $data['sectionCount']);
        // make sure each section has content
        $this->assertFalse(Section::where('content', '')->exists());
        $this->assertFalse(Section::whereNull('content')->exists());
    }
}

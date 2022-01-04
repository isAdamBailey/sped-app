<?php

namespace Tests\Feature;

use App\Models\Chapter;
use App\Models\Section;
use App\Models\State;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\Assert;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ChapterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testChaptersIndexPage()
    {
        $this->actingAs($user = User::factory()->create());

        $state = State::factory()->create();
        Chapter::factory()
            ->count(130)
            ->for($state)
            ->create();

        $response = $this->get(route('chapters.index'));

        $response->assertInertia(
            fn (Assert $chapter) => $chapter
                ->component('StateLaws')
                ->url('/chapters')
                ->has('chapters.data', 100)
                ->has('chapters.links')
                ->has('chapters.data.0.id')
                ->has('chapters.data.0.code')
                ->has('chapters.data.0.description')
                ->has(
                    'chapters.data.0.state',
                    fn (Assert $page) => $page
                        ->where('name', $state->name)
                        ->where('code_title', $state->code_title)
                        ->etc()
                )
        );
    }

    public function testSearchChaptersIndexPage()
    {
        $this->actingAs($user = User::factory()->create());

        $state = State::factory()->create();
        Chapter::factory()
            ->count(130)
            ->for($state)
            ->create();

        $searchTerm = 'qwerty';

        Chapter::factory()->for($state)->create([
            'description' => "description that has {$searchTerm} in the content",
        ]);

        $response = $this->get(route('chapters.index', ['search' => $searchTerm]));

        $response->assertInertia(
            fn (Assert $chapter) => $chapter
                ->component('StateLaws')
                ->url('/chapters?search='.$searchTerm)
                ->has('chapters.data', 1)
                ->has('chapters.links')
                ->has('chapters.data.0.id')
                ->has('chapters.data.0.code')
                ->has('chapters.data.0.description')
                ->has(
                    'chapters.data.0.state',
                    fn (Assert $page) => $page
                        ->where('name', $state->name)
                        ->where('code_title', $state->code_title)
                        ->etc()
                )
        );
    }

    public function testChapterShowPage()
    {
        $this->actingAs($user = User::factory()->create());

        $state = State::factory()->create();
        $chapter = Chapter::factory()
            ->for($state)
            ->has(Section::factory()->for($state)->count(5))
            ->create();

        $response = $this->get(route('chapters.show', $chapter));

        $response->assertInertia(
            fn (Assert $page) => $page
                ->component('Chapter')
                ->url('/chapters/'.$chapter->id)
                ->has('chapter.id')
                ->has('chapter.code')
                ->has('chapter.description')
                ->has('chapter.sections', 5)
                ->has('chapter.sections.0.code')
                ->has('chapter.sections.0.description')
                ->has('chapter.sections.0.content')
                ->has('chapter.sections.0.url')
                ->has(
                    'chapter.state',
                    fn (Assert $page) => $page
                        ->where('name', $state->name)
                        ->where('code_title', $state->code_title)
                        ->etc()
                )
        );
    }
}

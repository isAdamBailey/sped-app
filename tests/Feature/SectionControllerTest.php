<?php

namespace Tests\Feature;

use App\Models\Chapter;
use App\Models\Section;
use App\Models\State;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\Assert;
use Tests\TestCase;

class SectionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testSectionsIndexPage()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $state = State::factory()->create();
        $chapter = Chapter::factory()->for($state)->create();
        Section::factory()
            ->count(20)
            ->for($state)
            ->for($chapter)
            ->create();

        $response = $this->get(route('sections.index'));

        $response->assertInertia(
            fn (Assert $chapter) => $chapter
                ->component('Sections')
                ->url('/sections')
                ->has('sections.data', 15)
                ->has('sections.links')
                ->has('sections.data.0.slug')
                ->has('sections.data.0.code')
                ->has('sections.data.0.description')
                ->missing('sections.data.0.content')
                ->has(
                    'sections.data.0.state',
                    fn (Assert $page) => $page
                        ->where('name', $state->name)
                        ->where('code_title', $state->code_title)
                        ->etc()
                )
        );
    }

    public function testSectionsIndexPageDoesNotShowInactiveChapterSections()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $state = State::factory()->create();
        $chapter = Chapter::factory()->for($state)->create(['active' => 0]);
        Section::factory()
            ->count(20)
            ->for($state)
            ->for($chapter)
            ->create();

        $response = $this->get(route('sections.index'));

        $response->assertInertia(
            fn (Assert $chapter) => $chapter
                ->component('Sections')
                ->url('/sections')
                ->has('sections.data', 0)
        );
    }

    public function testSearchSectionsIndexPage()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $state = State::factory()->create();
        $chapter = Chapter::factory()->for($state)->create();
        Section::factory()
            ->count(130)
            ->for($state)
            ->for($chapter)
            ->create();

        $searchTerm = 'qwerty';

        Section::factory()->for($state)->for($chapter)->create([
            'content' => "content that has {$searchTerm} in the content",
        ]);

        $response = $this->get(route('sections.index', ['search' => $searchTerm]));

        $response->assertInertia(
            fn (Assert $chapter) => $chapter
                ->component('Sections')
                ->url('/sections?search='.$searchTerm)
                ->has('sections.data', 1)
                ->has('sections.links')
                ->has('sections.data.0.slug')
                ->has('sections.data.0.code')
                ->has('sections.data.0.description')
                ->missing('sections.data.0.content')
                ->has(
                    'sections.data.0.state',
                    fn (Assert $page) => $page
                        ->where('name', $state->name)
                        ->where('code_title', $state->code_title)
                        ->etc()
                )
        );
    }

    public function testFilterSectionsIndexPageByState()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $state = State::factory()->create();
        $chapter = Chapter::factory()->for($state)->create();
        Section::factory()
            ->count(10)
            ->for($state)
            ->for($chapter)
            ->create();

        $response = $this->get(route('sections.index', ['filter' => $state->name]));

        $response->assertInertia(
            fn (Assert $chapter) => $chapter
                ->component('Sections')
                ->url('/sections?filter='.$state->name)
                ->has('sections.data', 10)
                ->has('sections.links')
                ->has('sections.data.0.slug')
                ->has('sections.data.0.code')
                ->has('sections.data.0.description')
                ->missing('sections.data.0.content')
                ->has(
                    'sections.data.0.state',
                    fn (Assert $page) => $page
                        ->where('name', $state->name)
                        ->where('code_title', $state->code_title)
                        ->etc()
                )
        );

        $nonStateName = 'derp';
        $badResponse = $this->get(route('sections.index', ['filter' => $nonStateName]));

        $badResponse->assertInertia(
            fn (Assert $chapter) => $chapter
                ->component('Sections')
                ->url('/sections?filter='.$nonStateName)
                ->has('sections.data', 0)
                ->has('sections.links')
        );
    }

    public function testSectionShowPage()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $state = State::factory()->create();
        $chapter = Chapter::factory()->for($state)->create();

        $section = Section::factory()
            ->for($state)
            ->for($chapter)
            ->create();

        $response = $this->get(route('sections.show', $section));

        $response->assertInertia(
            fn (Assert $page) => $page
                ->component('Section')
                ->url('/sections/'.$section->slug)
                ->has('section.slug')
                ->has('section.code')
                ->has('section.description')
                ->has('section.content')
                ->has(
                    'section.state',
                    fn (Assert $page) => $page
                        ->where('name', $state->name)
                        ->where('code_title', $state->code_title)
                        ->etc()
                )
        );
    }
}

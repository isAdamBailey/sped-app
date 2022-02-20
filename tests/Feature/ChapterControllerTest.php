<?php

namespace Tests\Feature;

use App\Models\Chapter;
use App\Models\State;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ChapterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testChaptersIndexPageNotSuperAdmin()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $this->get(route('chapters.index'))->assertStatus(403);
    }

    public function testChaptersIndexPage()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
        $user->givePermissionTo('edit chapters');

        $state = State::factory()->create();
        Chapter::factory()
            ->count(20)
            ->for($state)
            ->create();

        $response = $this->get(route('chapters.index'));

        $response->assertInertia(
            fn (Assert $chapter) => $chapter
                ->component('Dashboard/Chapters/Index')
                ->url('/chapters')
                ->has('chapters.data', 15)
                ->has('chapters.links')
                ->has('chapters.data.0.slug')
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
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
        $user->givePermissionTo('edit chapters');

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
                ->component('Dashboard/Chapters/Index')
                ->url('/chapters?search='.$searchTerm)
                ->has('chapters.data', 1)
                ->has('chapters.links')
                ->has('chapters.data.0.slug')
                ->has('chapters.data.0.active')
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

    public function testFilterChaptersIndexPage()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
        $user->givePermissionTo('edit chapters');

        $state = State::factory()->create();
        Chapter::factory()
            ->count(10)
            ->for($state)
            ->create();

        $response = $this->get(route('chapters.index', ['filter' => $state->name]));

        $response->assertInertia(
            fn (Assert $chapter) => $chapter
                ->component('Dashboard/Chapters/Index')
                ->url('/chapters?filter='.$state->name)
                ->has('chapters.data', 10)
                ->has('chapters.links')
                ->has('chapters.data.0.slug')
                ->has('chapters.data.0.active')
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

        $nonStateName = 'kjhkjh';
        $badResponse = $this->get(route('chapters.index', ['filter' => $nonStateName]));

        $badResponse->assertInertia(
            fn (Assert $chapter) => $chapter
                ->component('Dashboard/Chapters/Index')
                ->url('/chapters?filter='.$nonStateName)
                ->has('chapters.data', 0)
                ->has('chapters.links')
        );
    }
}

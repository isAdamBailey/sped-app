<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\Assert;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Users who don't yet have a team should be faced with a screen
     * which should let them either create one, or go back to their
     * invitation email to join one they were invited to.
     */
    public function testRenderNewTeamForm()
    {
        $this->actingAs($user = User::factory()->create());

        $response = $this->get(route('create-first-team'));
        $response->assertInertia(
            fn (Assert $page) => $page
                ->component('Teams/Create')
                ->url('/create-first-team')
        );
    }

    public function testUserWithNoTeamIsRedirectedToNewTeamForm()
    {
        $this->actingAs($user = User::factory()->create());

        $this->get(route('dashboard'))
            ->assertRedirect(route('create-first-team'));
    }

    public function testUserWithTeamSeesDashboard()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $response = $this->get(route('dashboard'));

        $response->assertInertia(
            fn (Assert $page) => $page
                ->component('Dashboard/Show')
                ->url('/dashboard')
        );
    }
}

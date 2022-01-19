<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\Assert;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testUsersIndexPageNotSuperAdmin()
    {
        $this->actingAs(User::factory()->withPersonalTeam()->create());

        $this->get(route('users.index'))->assertStatus(403);
    }

    public function testUsersIndexPage()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
        $user->assignRole('super admin');

        User::factory()->count(20)->create();

        $response = $this->get(route('users.index'));

        $response->assertInertia(
            fn (Assert $user) => $user
                ->component('Dashboard/Users/Index')
                ->url('/users')
                ->has('users.data', 15)
                ->has('users.links')
                ->has('users.data.0.id')
                ->has('users.data.0.name')
                ->has('users.data.0.email')
                ->has('users.data.0.profile_photo_url')
                ->has('users.data.0.teams')
                ->has('users.data.0.roles')
                ->has('users.data.0.permissions')
        );
    }

    public function testSearchUsersIndexPage()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
        $user->assignRole('super admin');

        User::factory()->count(120)->create();

        $searchTerm = 'qwerty';

        User::factory()->create([
            'name' => "namehas {$searchTerm}",
        ]);

        $response = $this->get(route('users.index', ['search' => $searchTerm]));

        $response->assertInertia(
            fn (Assert $user) => $user
                ->component('Dashboard/Users/Index')
                ->url('/users?search='.$searchTerm)
                ->has('users.data', 1)
                ->has('users.links')
                ->has('users.data.0.id')
                ->has('users.data.0.name')
                ->has('users.data.0.email')
                ->has('users.data.0.profile_photo_url')
                ->has('users.data.0.teams')
                ->has('users.data.0.roles')
                ->has('users.data.0.permissions')
        );
    }

    public function testFilterUsersIndexPage()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
        $user->currentTeam->users()->attach(
            $user, ['role' => 'admin']
        );
        $user->assignRole('super admin');

        User::factory()->count(20)->create();
        $superAdminUser = User::factory()->create();
        $user->currentTeam->users()->attach(
            $superAdminUser, ['role' => 'editor']
        );
        $superAdminUser->assignRole('super admin');

        $response = $this->get(route('users.index', ['filter' => 'super_admin']));

        $response->assertInertia(
            fn (Assert $user) => $user
                ->component('Dashboard/Users/Index')
                ->url('/users?filter=super_admin')
                ->has('users.data', 2)
                ->has('users.links')
                ->has('users.data.0.id')
                ->has('users.data.0.name')
                ->has('users.data.0.email')
                ->has('users.data.0.profile_photo_url')
                ->has('users.data.0.teams')
                ->has(
                    'users.data.0.roles',
                    fn (Assert $page) => $page
                        ->where('0', 'super admin')
                        ->etc()
                )
                ->has('users.data.0.permissions')
        );

        $teamResponse = $this->get(route('users.index', ['filter' => 'team_admin']));

        $teamResponse->assertInertia(
            fn (Assert $user) => $user
                ->component('Dashboard/Users/Index')
                ->url('/users?filter=team_admin')
                ->has('users.data', 1)
                ->has('users.links')
        );

        $nonRoleName = 'kjhkjh';
        $badResponse = $this->get(route('users.index', ['filter' => $nonRoleName]));

        $badResponse->assertInertia(
            fn (Assert $user) => $user
                ->component('Dashboard/Users/Index')
                ->url('/users?filter='.$nonRoleName)
                ->has('users.data', 0)
                ->has('users.links')
        );
    }

    public function testUsersShowPageNotSuperAdmin()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $this->get(route('users.show', $user))->assertStatus(403);
    }

    public function testUserShowPage()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
        $user->assignRole('super admin');

        $secondUser = User::factory()->create();
        $user->currentTeam->users()->attach(
            $secondUser, ['role' => 'editor']
        );

        $response = $this->get(route('users.show', $secondUser));

        $response->assertInertia(
            fn (Assert $page) => $page
                ->component('Dashboard/Users/Show')
                ->url('/users/'.$secondUser->id)
                ->has('userObject.id')
                ->has('userObject.name')
                ->has('userObject.email')
                ->has('userObject.profile_photo_url')
                ->has('userObject.teams')
                ->has('userObject.roles_names')
                ->has('userObject.permissions_names')
        );
    }
}

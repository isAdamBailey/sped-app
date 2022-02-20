<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
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
        $user->givePermissionTo('edit users');

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
                ->has('users.data.0.permissions')
        );
    }

    public function testSearchUsersIndexPage()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
        $user->givePermissionTo('edit users');

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
                ->has('users.data.0.permissions')
        );
    }

    public function testFilterUsersIndexPage()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
        $user->currentTeam->users()->attach(
            $user, ['role' => 'admin']
        );
        $user->givePermissionTo(['edit users', 'edit chapters']);

        User::factory()->count(20)->create();
        $superAdminUser = User::factory()->create();
        $user->currentTeam->users()->attach(
            $superAdminUser, ['role' => 'editor']
        );
        $superAdminUser->givePermissionTo('edit chapters');

        $response = $this->get(route('users.index', ['filter' => 'edit_chapters']));

        $response->assertInertia(
            fn (Assert $user) => $user
                ->component('Dashboard/Users/Index')
                ->url('/users?filter=edit_chapters')
                ->has('users.data', 2)
                ->has('users.links')
                ->has('users.data.0.id')
                ->has('users.data.0.name')
                ->has('users.data.0.email')
                ->has('users.data.0.profile_photo_url')
                ->has('users.data.0.teams')
                ->has(
                    'users.data.0.permissions',
                    fn (Assert $page) => $page
                        ->where('0', 'edit chapters')
                        ->where('1', 'edit users')
                )
                ->has(
                    'users.data.1.permissions',
                    fn (Assert $page) => $page
                        ->where('0', 'edit chapters')
                )
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
        $user->givePermissionTo('edit users');

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
                ->has('userObject.permissions_names')
        );
    }

    public function testUpdatePermissions()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
        $user->givePermissionTo('edit users');

        $secondUser = User::factory()->create();

        $payload = ['permissions' => ['edit users']];

        $this->put(route('users.update', $secondUser), $payload)
            ->assertRedirect()
            ->assertSessionHas('flash.banner');

        $this->assertTrue($secondUser->hasPermissionTo('edit users'));
        $this->assertFalse($secondUser->hasPermissionTo('edit chapters'));
    }
}

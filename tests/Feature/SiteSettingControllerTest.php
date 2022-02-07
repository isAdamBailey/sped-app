<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\Assert;
use Tests\TestCase;

class SiteSettingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testSettingsShowPageNotPermissions()
    {
        $this->actingAs(User::factory()->withPersonalTeam()->create());

        $this->get(route('site-settings.show'))->assertStatus(403);
    }

    public function testSettingsShowPage()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
        $user->givePermissionTo('edit site settings');

        $response = $this->get(route('site-settings.show'));

        $response->assertInertia(
            fn (Assert $page) => $page
                ->component('Dashboard/Settings/Show')
                ->url('/site-settings')
                ->has('settings.id')
                ->has('settings.registration_active')
        );
    }

    public function testUpdateSettings()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
        $user->givePermissionTo('edit site settings');
        $setting = SiteSetting::factory()->create();

        $payload = ['registration_active' => false];

        $this->put(route('site-settings.update', $setting), $payload)
            ->assertRedirect()
            ->assertSessionHas('flash.banner');

        $this->assertTrue($setting->fresh()->registration_active === (int) $payload['registration_active']);
    }
}

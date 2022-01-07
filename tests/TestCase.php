<?php

namespace Tests;

use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\PermissionRegistrar;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();

        // re-register all the roles and permissions (clears cache and reloads relations)
        $this->artisan('db:seed', ['--class' => RoleAndPermissionSeeder::class]);
        $this->app->make(PermissionRegistrar::class)->registerPermissions();
    }
}

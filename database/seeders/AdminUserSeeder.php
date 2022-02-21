<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * This should only be run in production after the values have
     * been added to the .env file.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create([
            'email' => config('app.admin_email'),
            'name' => config('app.admin_name'),
        ]);
        $user->givePermissionTo(['edit users', 'edit chapters', 'edit site settings']);
    }
}

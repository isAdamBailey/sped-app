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
        $user = User::where('email', config('app.admin-email'))->first();
        $user->givePermissionTo(['edit users', 'edit chapters', 'edit site settings']);
    }
}

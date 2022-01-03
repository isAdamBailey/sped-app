<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create a registered user we can log in with
        User::factory()->withPersonalTeam()->create([
            'email' => 'test@test.com',
        ]);

        // fetch washington laws bby title
        Artisan::call('fetch:laws washington 28A');
    }
}

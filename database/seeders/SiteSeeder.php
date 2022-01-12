<?php

namespace Database\Seeders;

use App\Models\Document;
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
        $user = User::factory()->withPersonalTeam()->create([
            'email' => 'test@test.com',
        ]);

        $user->assignRole('super admin');

        Document::factory()->for($user->currentTeam)->count(5)->create();

        // fetch washington laws by title
        Artisan::call('fetch:laws washington 28A');
    }
}

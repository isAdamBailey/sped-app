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
        $user->currentTeam->users()->attach(
            $user, ['role' => 'admin']
        );
        $user->givePermissionTo(['edit users', 'edit chapters']);

        // create a team member
        $anotherUser = User::factory()->create([
            'email' => 'test2@test.com',
        ]);
        $user->currentTeam->users()->attach(
            $anotherUser, ['role' => 'editor']
        );

        Document::factory()->for($user->currentTeam)->count(5)->create();

        // fetch washington laws by title
        Artisan::call('fetch:laws washington 28A');
        Artisan::call('fetch:laws idea');
    }
}

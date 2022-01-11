<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\Assert;
use Tests\TestCase;

class DocumentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testChaptersIndexPageCanBeViewedByTeamMembers()
    {
        Storage::fake();

        $user = User::factory()->withPersonalTeam()->create();

        // add a team member and let them view the documents
        $user->currentTeam->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'editor']
        );
        $otherUser->switchTeam($user->currentTeam);

        $this->actingAs($otherUser);

        Document::factory()
            ->count(20)
            ->for($user->currentTeam)
            ->create();

        $response = $this->get(route('documents.index'))->assertStatus(200);

        $response->assertInertia(
            fn (Assert $chapter) => $chapter
                ->component('Documents')
                ->url('/documents')
                ->has('documents.data', 15)
                ->has('documents.links')
                ->has('documents.data.0.id')
                ->has('documents.data.0.name')
                ->has('documents.data.0.description')
                ->has('documents.data.0.next_action_date')
        );
    }
}

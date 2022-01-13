<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\Assert;
use Tests\TestCase;

class DocumentControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testChaptersIndexPageCannotBeViewedByNonTeamMembers()
    {
        $user = User::factory()->withPersonalTeam()->create();

        Document::factory()
            ->count(20)
            ->for($user->currentTeam)
            ->create();

        // a user on a different team should not be able to see any of these documents
        $this->actingAs($otherUser = User::factory()->withPersonalTeam()->create());

        $response = $this->get(route('documents.index'))->assertStatus(200);

        $response->assertInertia(
            fn (Assert $chapter) => $chapter
                ->component('Documents')
                ->url('/documents')
                ->has('documents.data', 0)
                ->has('documents.links')
        );
    }

    public function testChaptersIndexPageCanBeViewedByTeamMembers()
    {
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
                ->has('documents.data.0.file_url')
        );
    }

    public function testDocumentShowPageNotAvailableToUnauthenticated()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $document = Document::factory()->for($user->currentTeam)->create();

        $this->actingAs($otherUser = User::factory()->withPersonalTeam()->create());

        $this->get(route('documents.show', $document))
            ->assertRedirect()
            ->assertSessionHas('flash.banner');
    }

    public function testDocumentShowPageIsAvailableToAuthenticated()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $document = Document::factory()->for($user->currentTeam)->create();

        $response = $this->get(route('documents.show', $document));

        $response->assertInertia(
            fn (Assert $page) => $page
                ->component('Document')
                ->url('/documents/'.$document->id)
                ->has('document.id')
                ->has('document.name')
                ->has('document.next_action_date')
                ->has('document.description')
                ->has('document.file_url')
                ->has(
                    'document.team',
                    fn (Assert $page) => $page
                        ->where('name', $user->currentTeam->name)
                        ->etc()
                )
        );
    }

    public function testUserCanCreateDocument()
    {
        Storage::fake('s3');

        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $request = [
            'document' => UploadedFile::fake()->create('document.pdf', 200, 'application/pdf'),
            'name' => $this->faker->word(),
            'description' => $this->faker->sentences(4, true),
            'next_action_date' => $this->faker->date(),
        ];

        $this->post(route('documents.store'), $request)
            ->assertRedirect(route('documents.index'))
            ->assertSessionHas('flash.banner');

        $filePath = 'documents/'.$user->currentTeam->id.'/'.$request['document']->hashName();
        Storage::disk('s3')->assertExists($filePath);

        $document = Document::first();
        $this->assertEquals($document->file_path, $filePath);
        $this->assertEquals($document->name, ucfirst($request['name']));
        $this->assertEquals($document->description, $request['description']);
        $this->assertEquals($document->next_action_date, $request['next_action_date'].' 00:00:00');
    }

    public function testUserCanUpdateDocument()
    {
        Storage::fake('s3');

        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $document = Document::factory()->for($user->currentTeam)->create([
            'file_path' => 'fake/file/path',
        ]);

        $request = [
            'document' => UploadedFile::fake()->create('document.pdf', 200, 'application/pdf'),
            'name' => $this->faker->word(),
            'description' => $this->faker->sentences(4, true),
            'next_action_date' => $this->faker->date(),
        ];

        $this->put(route('documents.update', $document), $request)
            ->assertRedirect(route('documents.show', $document))
            ->assertSessionHas('flash.banner');

        $filePath = 'documents/'.$user->currentTeam->id.'/'.$request['document']->hashName();
        Storage::disk('s3')->assertExists($filePath);

        $document = $document->fresh();
        $this->assertEquals($document->file_path, $filePath);
        $this->assertEquals($document->name, ucfirst($request['name']));
        $this->assertEquals($document->description, $request['description']);
        $this->assertEquals($document->next_action_date, $request['next_action_date'].' 00:00:00');
    }

    public function testDocumentCanBeDestroyed()
    {
        Storage::fake('s3');

        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $file = UploadedFile::fake()->create('document.pdf', 200, 'application/pdf');
        $filePath = 'documents/'.$user->currentTeam->id.'/'.$file->hashName();

        $document = Document::factory()->for($user->currentTeam)->create([
            'file_path' => $filePath,
        ]);

        $response = $this->delete(route('documents.destroy', $document));

        $response->assertRedirect(route('documents.index'))
            ->assertSessionHas('flash.banner');

        Storage::disk('s3')->assertMissing($filePath);

        $this->assertDeleted($document);
    }
}

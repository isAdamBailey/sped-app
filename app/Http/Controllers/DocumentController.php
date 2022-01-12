<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DocumentController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->search;

        $documents = Document::where('team_id', auth()->user()->currentTeam->id)
            ->when(
                $search,
                fn ($query) => $query->where('name', 'LIKE', '%'.$search.'%')
                    ->orWhere('description', 'LIKE', '%'.$search.'%')
            )
            ->orderBy('next_action_date')
            ->paginate();

        return Inertia::render('Documents', [
            'documents' => $documents->through(fn ($document) => [
                'id' => $document->id,
                'name' => $document->name,
                'description' => $document->description,
                'next_action_date' => $document->next_action_date,
                'file_url' => $document->file_url,
            ]),
            'search' => $search,
        ]);
    }

    public function show(Document $document): Response|RedirectResponse
    {
        if (auth()->user()->currentTeam->id !== $document->team->id) {
            return back()->with([
                'flash.bannerStyle' => 'error',
                'flash.banner' => 'You don\'t have access to this document!',
            ]);
        }

        return Inertia::render('Document', [
            'document' => $document->load('team')
                ->only('id', 'file_url', 'name', 'description', 'next_action_date', 'team'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string',
            'next_action_date' => 'date|nullable',
            'document' => [
                'file',
                'mimes:pdf,vnd.openxmlformats-officedocument.wordprocessingml.document',
                'max:2000',
                'nullable',
            ],
        ]);

        $userTeam = auth()->user()->currentTeam;

        $filePath = null;
        if ($request->hasFile('document')) {
            $filePath = $request->file('document')->storePublicly('documents/'.$userTeam->id);
        }

        $actionDate = null;
        if ($request->next_action_date) {
            $actionDate = Carbon::parse($request->next_action_date)->toDateString();
        }

        $userTeam->documents()->create([
            'name' => $request->name,
            'description' => $request->description,
            'next_action_date' => $actionDate,
            'file_path' => $filePath,
        ]);

        return redirect(route('documents.index'))
            ->with('flash.banner', 'Document successfully uploaded!');
    }
}

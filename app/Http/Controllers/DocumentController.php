<?php

namespace App\Http\Controllers;

use App\Models\Document;
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
                'file_path' => $document->file_path,
            ]),
            'search' => $search,
        ]);
    }
}

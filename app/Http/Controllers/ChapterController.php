<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\State;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ChapterController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->search;
        $filter = $request->filter;

        $chapters = Chapter::with('state')
            ->whereActive('1')
            ->when(
                $search,
                fn ($query) => $query->where('code', 'LIKE', '%'.$search.'%')
                    ->orWhere('description', 'LIKE', '%'.$search.'%')
            )
            ->when(
                $filter,
                fn ($query) => $query->whereHas('state', fn ($q) => $q->where('states.name', strtolower($filter))),
            )
            ->paginate(100);

        return Inertia::render('Chapters', [
            'chapters' => $chapters->through(fn ($chapter) => [
                'id' => $chapter->id,
                'code' => $chapter->code,
                'description' => $chapter->description,
                'state' => $chapter->state,
            ]),
            'search' => $search,
            'filter' => $filter,
        ]);
    }

    public function show(Chapter $chapter): Response
    {
        return Inertia::render('Chapter', [
            'chapter' => $chapter->load(['state', 'sections']),
        ]);
    }
}

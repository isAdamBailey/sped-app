<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ChapterController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->search;

        $chapters = Chapter::with('state')
            ->whereActive('1')
            ->when(
                $search,
                fn ($query) => $query->where('code', 'LIKE', '%'.$search.'%')
                    ->orWhere('description', 'LIKE', '%'.$search.'%')
            )
            ->paginate(100);

        return Inertia::render('StateLaws', [
            'chapters' => $chapters->through(fn ($chapter) => [
                'id' => $chapter->id,
                'code' => $chapter->code,
                'description' => $chapter->description,
                'state' => $chapter->state,
            ]),
            'search' => $search,
        ]);
    }

    public function show(Chapter $chapter): Response
    {
        return Inertia::render('Chapter', [
            'chapter' => $chapter->load(['state', 'sections']),
        ]);
    }
}

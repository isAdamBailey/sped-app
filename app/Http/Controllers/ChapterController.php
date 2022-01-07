<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Inertia\Inertia;
use Inertia\Response;

class ChapterController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->search;
        $filter = $request->filter;

        $chapters = Chapter::with('state')
            ->when( // only show active chapters for non-admins
                ! auth()->user()->hasRole('super-admin'),
                fn ($query) => $query->whereActive('1')
            )
            ->when(
                $search,
                fn ($query) => $query->where('code', 'LIKE', '%'.$search.'%')
                    ->orWhere('description', 'LIKE', '%'.$search.'%')
            )
            ->when(
                $filter,
                fn ($query) => $query->whereHas('state', fn ($q) => $q->where('states.name', strtolower($filter))),
            )
            ->paginate();

        return Inertia::render('Chapters/Chapters', [
            'chapters' => $chapters->through(fn ($chapter) => [
                'slug' => $chapter->slug,
                'code' => $chapter->code,
                'description' => $chapter->description,
                'state' => $chapter->state,
                'active' => $chapter->active,
            ]),
            'search' => $search,
            'filter' => $filter,
        ]);
    }

    public function show(Chapter $chapter): Response
    {
        return Inertia::render('Chapters/Chapter', [
            'chapter' => $chapter->load(['state', 'sections'])
                ->only('slug', 'code', 'active', 'description', 'title_id', 'state', 'sections'),
        ]);
    }

    public function update(Request $request, Chapter $chapter): Redirector|Application|RedirectResponse
    {
        $request->validate([
            'active' => 'boolean',
        ]);

        if ($request->has('active')) {
            $chapter->active = $request->active;
        }

        $chapter->save();

        return redirect(route('chapters.index', $chapter->fresh()))
            ->with('flash.banner', 'Chapter successfully updated!');
    }
}

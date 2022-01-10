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

        return Inertia::render('Dashboard/Chapters/Index', [
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

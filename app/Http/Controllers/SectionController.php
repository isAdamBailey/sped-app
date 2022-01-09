<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SectionController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->search;
        $filter = $request->filter;

        $sections = Section::with('state')
            ->whereHas('chapter', fn ($q) => $q->where('active', 1))
            ->when(
                $search,
                fn ($query) => $query->where('code', 'LIKE', '%'.$search.'%')
                    ->orWhere('description', 'LIKE', '%'.$search.'%')
                    ->orWhere('content', 'LIKE', '%'.$search.'%')
            )
            ->when(
                $filter,
                fn ($query) => $query->whereHas('state', fn ($q) => $q->where('states.name', strtolower($filter))),
            )
            ->paginate();

        return Inertia::render('Sections', [
            'sections' => $sections->through(fn ($section) => [
                'slug' => $section->slug,
                'code' => $section->code,
                'description' => $section->description,
                'state' => $section->state,
            ]),
            'search' => $search,
            'filter' => $filter,
        ]);
    }

    public function show(Section $section): Response
    {
        return Inertia::render('Section', [
            'section' => $section->load(['state', 'chapter'])
                ->only('slug', 'url', 'code', 'description', 'content', 'state', 'chapter'),
        ]);
    }
}

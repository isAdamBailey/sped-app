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
        $filter = $request->filter;
        $search = $request->search;

        $sections = Section::with('state')
            ->active()
            ->search($search)
            ->filterState($filter)
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

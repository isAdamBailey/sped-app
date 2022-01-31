<?php

namespace App\Http\Controllers;

use App\Http\Resources\SectionResource;
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
            'sections' => SectionResource::collection($sections),
            'search' => $search,
            'filter' => $filter,
        ]);
    }

    public function show(Section $section): Response
    {
        $section->include_content = true; // tell resource to include the content column

        return Inertia::render('Section', [
            'section' => SectionResource::make($section->load(['state', 'chapter'])),
        ]);
    }
}

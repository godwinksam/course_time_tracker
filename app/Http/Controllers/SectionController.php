<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Section::with(['subSections' => function ($query) {
            $query->orderBy('order');
        }])->orderBy('order')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'integer',
        ]);

        $section = Section::create($validated);
        return $section->fresh(['subSections']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Section $section)
    {
        return $section->load(['subSections']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Section $section)
    {
        $validated = $request->validate([
            'title' => 'string|max:255',
            'order' => 'integer',
        ]);

        $section->update($validated);
        return $section->fresh(['subSections']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $section)
    {
        $section->delete();
        return response()->noContent();
    }
}

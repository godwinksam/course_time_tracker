<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\SubSection;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $sections = Section::with(['subSections' => function ($query) {
            $query->orderBy('order');
        }])->orderBy('order')->get();

        return response()->json($sections);
    }

    public function updateProgress(Request $request, SubSection $subSection)
    {
        $validated = $request->validate([
            'is_completed' => 'required|boolean',
        ]);

        $subSection->update([
            'is_completed' => $validated['is_completed'],
            'completed_at' => $validated['is_completed'] ? now() : null,
        ]);

        // Logic for "Uncheck Below"
        if (!$validated['is_completed']) {
            SubSection::where('section_id', $subSection->section_id)
                ->where('order', '>', $subSection->order)
                ->update(['is_completed' => false, 'completed_at' => null]);
        }

        return response()->json(['message' => 'Progress updated']);
    }
}

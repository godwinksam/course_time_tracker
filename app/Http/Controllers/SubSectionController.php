<?php

namespace App\Http\Controllers;

use App\Models\SubSection;
use Illuminate\Http\Request;

class SubSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Not really implemented independently, typically accessed via parent
        return SubSection::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'title' => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
            'order' => 'integer',
        ]);

        $subSection = SubSection::create($validated);
        return $subSection;
    }

    /**
     * Display the specified resource.
     */
    public function show(SubSection $subSection)
    {
        return $subSection;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubSection $subSection)
    {
        $validated = $request->validate([
            'title' => 'string|max:255',
            'duration_minutes' => 'integer|min:1',
            'section_id' => 'exists:sections,id',
            'order' => 'integer',
            'is_completed' => 'boolean',
        ]);

        // Logic for completion status update
        if (isset($validated['is_completed'])) {
            $isCompleted = $validated['is_completed'];
            $subSection->update([
                'is_completed' => $isCompleted,
                'completed_at' => $isCompleted ? now() : null,
            ]);

            // If unchecking, uncheck all sub-sections with a higher order number globally
            if (!$isCompleted) {
                SubSection::where('order', '>', $subSection->order)
                    ->update(['is_completed' => false, 'completed_at' => null]);
            }
        }
        
        // Remove is_completed from validated array if handled separately to avoid overwriting completed_at if not intended?
        // Actually, update merges, so it's fine.
        $subSection->update($validated);

        return $subSection; 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubSection $subSection)
    {
        $subSection->delete();
        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\SubSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Returns completed sub-sections grouped by day (completed_at date)
     * within a given date range.
     *
     * Query params: from (Y-m-d), to (Y-m-d)
     */
    public function dailyProgress(Request $request)
    {
        $request->validate([
            'from' => 'required|date',
            'to'   => 'required|date|after_or_equal:from',
        ]);

        $from = $request->input('from');
        $to   = $request->input('to');

        $rows = SubSection::select(
                DB::raw('DATE(completed_at) as date'),
                DB::raw('SUM(duration_minutes) as total_minutes'),
                DB::raw('COUNT(*) as total_items')
            )
            ->where('is_completed', true)
            ->whereNotNull('completed_at')
            ->whereDate('completed_at', '>=', $from)
            ->whereDate('completed_at', '<=', $to)
            ->groupBy(DB::raw('DATE(completed_at)'))
            ->orderBy('date')
            ->get();

        return response()->json($rows);
    }

    /**
     * Returns a list of completed sub-sections within the date range,
     * with their section name, title, duration, and completed_at.
     */
    public function completedItems(Request $request)
    {
        $request->validate([
            'from' => 'required|date',
            'to'   => 'required|date|after_or_equal:from',
        ]);

        $from = $request->input('from');
        $to   = $request->input('to');

        $items = SubSection::with('section:id,title')
            ->where('is_completed', true)
            ->whereNotNull('completed_at')
            ->whereDate('completed_at', '>=', $from)
            ->whereDate('completed_at', '<=', $to)
            ->orderBy('completed_at')
            ->get(['id', 'section_id', 'title', 'duration_minutes', 'order', 'completed_at']);

        return response()->json($items);
    }
}

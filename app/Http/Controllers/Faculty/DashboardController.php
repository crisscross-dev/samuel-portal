<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $faculty = auth()->user()->faculty;
        $semester = Semester::current();

        $stats = [
            'total_sections' => 0,
            'total_students' => 0,
            'pending_grades' => 0,
        ];

        if ($faculty) {
            $loads = $faculty->currentTeachingLoads()->get();

            $stats['total_sections'] = $loads->count();
            $stats['total_students'] = $loads->sum(fn ($ss) => $ss->enrolledCount());
            $stats['pending_grades'] = $faculty->grades()->where('is_finalized', false)->count();
        } else {
            $loads = collect();
        }

        return view('faculty.dashboard', compact('loads', 'stats', 'semester'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Semester;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AcademicYearController extends Controller
{
    public function index(): View
    {
        $academicYears = AcademicYear::with('semesters')->latest()->paginate(10);
        return view('admin.academic-years.index', compact('academicYears'));
    }

    public function create(): View
    {
        return view('admin.academic-years.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:50'],
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after:start_date'],
            'is_active'  => ['boolean'],
        ]);

        $ay = AcademicYear::create($request->only('name', 'start_date', 'end_date', 'is_active'));

        if ($request->boolean('is_active')) {
            AcademicYear::activateOnly($ay->id);
        }

        // Auto-create semesters
        if ($request->boolean('auto_semesters')) {
            $midpoint = $ay->start_date->copy()->addMonths(5);
            Semester::create([
                'academic_year_id' => $ay->id,
                'name'             => '1st Semester',
                'start_date'       => $ay->start_date,
                'end_date'         => $midpoint,
            ]);
            Semester::create([
                'academic_year_id' => $ay->id,
                'name'             => '2nd Semester',
                'start_date'       => $midpoint->copy()->addDay(),
                'end_date'         => $ay->end_date,
            ]);
        }

        return redirect()->route('admin.academic-years.index')
            ->with('success', 'Academic year created successfully.');
    }

    public function edit(AcademicYear $academicYear): View
    {
        $academicYear->load('semesters');
        return view('admin.academic-years.edit', compact('academicYear'));
    }

    public function update(Request $request, AcademicYear $academicYear): RedirectResponse
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:50'],
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after:start_date'],
            'is_active'  => ['boolean'],
        ]);

        $academicYear->update($request->only('name', 'start_date', 'end_date', 'is_active'));

        if ($request->boolean('is_active')) {
            AcademicYear::activateOnly($academicYear->id);
        }

        return redirect()->route('admin.academic-years.index')
            ->with('success', 'Academic year updated successfully.');
    }

    public function destroy(AcademicYear $academicYear): RedirectResponse
    {
        $academicYear->delete();
        return redirect()->route('admin.academic-years.index')
            ->with('success', 'Academic year deleted successfully.');
    }
}

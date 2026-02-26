<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTuitionStructureRequest;
use App\Models\AcademicYear;
use App\Models\Department;
use App\Models\GradeLevel;
use App\Models\Program;
use App\Models\TuitionStructure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PricingController extends Controller
{
    /**
     * List all tuition structures.
     */
    public function index(Request $request): View
    {
        $query = TuitionStructure::with(['department', 'academicYear', 'gradeLevel', 'program'])
            ->latest();

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        $structures    = $query->paginate(15);
        $departments   = Department::active()->orderBy('name')->get();
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();

        return view('admin.pricing.index', compact('structures', 'departments', 'academicYears'));
    }

    /**
     * Show form to create a new tuition structure.
     */
    public function create(): View
    {
        $departments   = Department::active()->orderBy('name')->get();
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $gradeLevels   = GradeLevel::with('department')->orderBy('level_order')->get();
        $programs      = Program::orderBy('name')->get();

        return view('admin.pricing.create', compact('departments', 'academicYears', 'gradeLevels', 'programs'));
    }

    /**
     * Store a new tuition structure (deactivates previous for same dept+year).
     */
    public function store(StoreTuitionStructureRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            TuitionStructure::where('department_id', $request->department_id)
                ->where('academic_year_id', $request->academic_year_id)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            $data = $request->validated();
            if ($data['pricing_type'] === 'flat') {
                $data['lecture_rate'] = null;
                $data['lab_rate']     = null;
            } else {
                $data['flat_amount'] = null;
            }
            $data['is_active'] = true;
            TuitionStructure::create($data);
        });

        return redirect()->route('admin.pricing.index')
            ->with('success', 'Tuition structure created and activated.');
    }

    /**
     * Read-only detail view.
     */
    public function show(TuitionStructure $pricing): View
    {
        $pricing->load(['department', 'academicYear', 'gradeLevel', 'program']);
        return view('admin.pricing.show', compact('pricing'));
    }

    /**
     * Edit form.
     */
    public function edit(TuitionStructure $pricing): View
    {
        $departments   = Department::active()->orderBy('name')->get();
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $gradeLevels   = GradeLevel::with('department')->orderBy('level_order')->get();
        $programs      = Program::orderBy('name')->get();

        return view('admin.pricing.edit', compact('pricing', 'departments', 'academicYears', 'gradeLevels', 'programs'));
    }

    /**
     * Update: deactivate old, write fresh revision.
     */
    public function update(StoreTuitionStructureRequest $request, TuitionStructure $pricing): RedirectResponse
    {
        DB::transaction(function () use ($request, $pricing) {
            TuitionStructure::where('department_id', $request->department_id)
                ->where('academic_year_id', $request->academic_year_id)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            $data = $request->validated();
            if ($data['pricing_type'] === 'flat') {
                $data['lecture_rate'] = null;
                $data['lab_rate']     = null;
            } else {
                $data['flat_amount'] = null;
            }
            $data['is_active'] = true;
            $pricing->update($data);
        });

        return redirect()->route('admin.pricing.index')
            ->with('success', 'Tuition structure updated.');
    }

    /**
     * Toggle active state.
     */
    public function toggleActive(TuitionStructure $pricing): RedirectResponse
    {
        $pricing->update(['is_active' => !$pricing->is_active]);
        $state = $pricing->fresh()->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Tuition structure {$state}.");
    }

    /**
     * Delete (only if no enrollments linked).
     */
    public function destroy(TuitionStructure $pricing): RedirectResponse
    {
        if ($pricing->enrollments()->exists()) {
            return back()->with('error', 'Cannot delete — enrollments are linked to this pricing structure.');
        }
        $pricing->delete();
        return redirect()->route('admin.pricing.index')->with('success', 'Tuition structure deleted.');
    }
}

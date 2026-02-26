<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\GradeLevel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GradeLevelController extends Controller
{
    public function index(Request $request): View
    {
        $query = GradeLevel::with('department')->withCount('sections');

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $gradeLevels = $query->orderBy('department_id')->orderBy('level_order')->get();
        $departments = Department::active()->orderBy('name')->get();

        return view('registrar.grade-levels.index', compact('gradeLevels', 'departments'));
    }

    public function create(): View
    {
        $departments = Department::active()->orderBy('name')->get();
        return view('registrar.grade-levels.create', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'department_id' => ['required', 'exists:departments,id'],
            'name'          => ['required', 'string', 'max:100'],
            'level_order'   => ['required', 'integer', 'min:1'],
            'is_active'     => ['boolean'],
        ]);

        GradeLevel::create($request->only('department_id', 'name', 'level_order', 'is_active'));

        return redirect()->route('registrar.grade-levels.index')
            ->with('success', 'Grade level created successfully.');
    }

    public function edit(GradeLevel $gradeLevel): View
    {
        $departments = Department::active()->orderBy('name')->get();
        return view('registrar.grade-levels.edit', compact('gradeLevel', 'departments'));
    }

    public function update(Request $request, GradeLevel $gradeLevel): RedirectResponse
    {
        $request->validate([
            'department_id' => ['required', 'exists:departments,id'],
            'name'          => ['required', 'string', 'max:100'],
            'level_order'   => ['required', 'integer', 'min:1'],
            'is_active'     => ['boolean'],
        ]);

        $gradeLevel->update($request->only('department_id', 'name', 'level_order', 'is_active'));

        return redirect()->route('registrar.grade-levels.index')
            ->with('success', 'Grade level updated successfully.');
    }

    public function destroy(GradeLevel $gradeLevel): RedirectResponse
    {
        if ($gradeLevel->sections()->exists()) {
            return back()->with('error', 'Cannot delete grade level with existing sections.');
        }

        $gradeLevel->delete();

        return redirect()->route('registrar.grade-levels.index')
            ->with('success', 'Grade level deleted successfully.');
    }
}

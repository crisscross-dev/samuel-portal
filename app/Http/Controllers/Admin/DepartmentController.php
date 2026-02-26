<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(): View
    {
        $departments = Department::withCount('gradeLevels', 'faculty')
            ->with('headFaculty.user')
            ->latest()
            ->get();

        return view('admin.departments.index', compact('departments'));
    }

    public function create(): View
    {
        return view('admin.departments.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'code'        => ['required', 'string', 'max:20', 'unique:departments,code'],
            'description' => ['nullable', 'string', 'max:255'],
            'is_active'   => ['boolean'],
        ]);

        Department::create($request->only('name', 'code', 'description', 'is_active'));

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department created successfully.');
    }

    public function edit(Department $department): View
    {
        // Show active faculty belonging to THIS department for head assignment
        $facultyList = Faculty::with('user')
            ->where('department_id', $department->id)
            ->where('is_active', true)
            ->get();

        return view('admin.departments.edit', compact('department', 'facultyList'));
    }

    public function update(Request $request, Department $department): RedirectResponse
    {
        $request->validate([
            'name'            => ['required', 'string', 'max:100'],
            'code'            => ['required', 'string', 'max:20', 'unique:departments,code,' . $department->id],
            'description'     => ['nullable', 'string', 'max:255'],
            'is_active'       => ['boolean'],
            'head_faculty_id' => ['nullable', 'exists:faculty,id'],
        ]);

        // Validate head belongs to this department and is active
        if ($request->filled('head_faculty_id')) {
            $head = Faculty::find($request->head_faculty_id);
            if (!$head || $head->department_id !== $department->id || !$head->is_active) {
                return back()->withErrors(['head_faculty_id' => 'Selected faculty must be an active member of this department.']);
            }
        }

        $department->update($request->only('name', 'code', 'description', 'is_active', 'head_faculty_id'));

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        if ($department->gradeLevels()->exists()) {
            return back()->with('error', 'Cannot delete department with grade levels.');
        }

        if ($department->faculty()->exists()) {
            return back()->with('error', 'Cannot delete department with assigned faculty members.');
        }

        $department->delete();

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department deleted successfully.');
    }
}

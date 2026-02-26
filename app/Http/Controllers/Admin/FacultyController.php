<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateFacultyRequest;
use App\Http\Requests\Admin\UpdateFacultyRequest;
use App\Models\Department;
use App\Models\Faculty;
use App\Services\FacultyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FacultyController extends Controller
{
    public function __construct(
        protected FacultyService $facultyService,
    ) {}

    /**
     * List all faculty members with filtering.
     */
    public function index(): View
    {
        $faculty = Faculty::with(['user', 'department', 'headedDepartment'])
            ->latest()
            ->paginate(20);

        $departments = Department::active()->orderBy('name')->get();

        return view('admin.faculty.index', compact('faculty', 'departments'));
    }

    /**
     * Show the form to create a new faculty member.
     */
    public function create(): View
    {
        $departments   = Department::active()->orderBy('name')->get();
        $nextEmployeeId = $this->facultyService->generateEmployeeId();

        return view('admin.faculty.create', compact('departments', 'nextEmployeeId'));
    }

    /**
     * Store new faculty (User + Faculty) inside a DB transaction.
     */
    public function store(CreateFacultyRequest $request): RedirectResponse
    {
        $this->facultyService->createFaculty($request->validated());

        return redirect()->route('admin.faculty.index')
            ->with('success', 'Faculty account created successfully.');
    }

    /**
     * Show faculty details.
     */
    public function show(Faculty $faculty): View
    {
        $faculty->load([
            'user',
            'department',
            'headedDepartment',
            'sectionSubjects.section.gradeLevel',
            'sectionSubjects.subject',
        ]);

        return view('admin.faculty.show', compact('faculty'));
    }

    /**
     * Show the form to edit a faculty member.
     */
    public function edit(Faculty $faculty): View
    {
        $faculty->load(['user', 'department']);
        $departments = Department::active()->orderBy('name')->get();

        return view('admin.faculty.edit', compact('faculty', 'departments'));
    }

    /**
     * Update faculty (User + Faculty) inside a DB transaction.
     */
    public function update(UpdateFacultyRequest $request, Faculty $faculty): RedirectResponse
    {
        $this->facultyService->updateFaculty($faculty, $request->validated());

        return redirect()->route('admin.faculty.index')
            ->with('success', 'Faculty account updated successfully.');
    }

    /**
     * Toggle faculty active status (soft deactivate/reactivate).
     */
    public function toggleActive(Faculty $faculty): RedirectResponse
    {
        $this->facultyService->toggleActive($faculty);

        $status = $faculty->fresh()->is_active ? 'reactivated' : 'deactivated';

        return back()->with('success', "Faculty account {$status} successfully.");
    }

    /**
     * Delete faculty record (soft delete).
     */
    public function destroy(Faculty $faculty): RedirectResponse
    {
        // Prevent deleting faculty with active teaching loads
        if ($faculty->sectionSubjects()->exists()) {
            return back()->with('error', 'Cannot delete faculty with active teaching assignments. Deactivate instead.');
        }

        // Remove as department head if applicable
        $faculty->headedDepartment?->update(['head_faculty_id' => null]);

        // Soft-delete faculty and deactivate user
        $faculty->delete();
        $faculty->user->update(['is_active' => false]);

        return redirect()->route('admin.faculty.index')
            ->with('success', 'Faculty account deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Faculty;
use App\Models\GradeLevel;
use App\Models\Section;
use App\Models\SectionSubject;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SectionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Section::with(['gradeLevel.department', 'academicYear', 'adviser.user'])
            ->withCount('sectionSubjects', 'students');

        if ($request->filled('grade_level_id')) {
            $query->where('grade_level_id', $request->grade_level_id);
        }

        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        $sections      = $query->latest()->paginate(15)->withQueryString();
        $gradeLevels   = GradeLevel::with('department')->active()->orderBy('department_id')->orderBy('level_order')->get();
        $academicYears = AcademicYear::orderByDesc('start_date')->get();

        return view('registrar.sections.index', compact('sections', 'gradeLevels', 'academicYears'));
    }

    public function create(): View
    {
        $gradeLevels   = GradeLevel::with('department')->active()->orderBy('department_id')->orderBy('level_order')->get();
        $academicYears = AcademicYear::orderByDesc('start_date')->get();
        $faculty       = Faculty::with('user')->where('is_active', true)->get();
        $subjects      = Subject::where('is_active', true)->orderBy('code')->get();

        return view('registrar.sections.create', compact('gradeLevels', 'academicYears', 'faculty', 'subjects'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'             => ['required', 'string', 'max:50'],
            'grade_level_id'   => ['required', 'exists:grade_levels,id'],
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'adviser_id'       => ['nullable', 'exists:faculty,id'],
            'max_students'     => ['required', 'integer', 'min:1', 'max:100'],
            'subjects'         => ['nullable', 'array'],
            'subjects.*.subject_id' => ['required_with:subjects', 'exists:subjects,id'],
            'subjects.*.faculty_id' => ['nullable', 'exists:faculty,id'],
            'subjects.*.schedule'   => ['nullable', 'string', 'max:100'],
            'subjects.*.room'       => ['nullable', 'string', 'max:50'],
        ]);

        $section = Section::create($request->only([
            'name', 'grade_level_id', 'academic_year_id', 'adviser_id', 'max_students',
        ]));

        // Create section subjects
        if ($request->has('subjects')) {
            foreach ($request->subjects as $subjectData) {
                if (!empty($subjectData['subject_id'])) {
                    SectionSubject::create([
                        'section_id' => $section->id,
                        'subject_id' => $subjectData['subject_id'],
                        'faculty_id' => $subjectData['faculty_id'] ?? null,
                        'schedule'   => $subjectData['schedule'] ?? null,
                        'room'       => $subjectData['room'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('registrar.sections.show', $section)
            ->with('success', 'Section created successfully.');
    }

    public function show(Section $section): View
    {
        $section->load([
            'gradeLevel.department',
            'academicYear',
            'adviser.user',
            'sectionSubjects.subject',
            'sectionSubjects.faculty.user',
            'students.user',
        ]);

        return view('registrar.sections.show', compact('section'));
    }

    public function edit(Section $section): View
    {
        $section->load('sectionSubjects');
        $gradeLevels   = GradeLevel::with('department')->active()->orderBy('department_id')->orderBy('level_order')->get();
        $academicYears = AcademicYear::orderByDesc('start_date')->get();
        $faculty       = Faculty::with('user')->where('is_active', true)->get();
        $subjects      = Subject::where('is_active', true)->orderBy('code')->get();

        return view('registrar.sections.edit', compact('section', 'gradeLevels', 'academicYears', 'faculty', 'subjects'));
    }

    public function update(Request $request, Section $section): RedirectResponse
    {
        $request->validate([
            'name'             => ['required', 'string', 'max:50'],
            'grade_level_id'   => ['required', 'exists:grade_levels,id'],
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'adviser_id'       => ['nullable', 'exists:faculty,id'],
            'max_students'     => ['required', 'integer', 'min:1', 'max:100'],
            'subjects'         => ['nullable', 'array'],
            'subjects.*.subject_id' => ['required_with:subjects', 'exists:subjects,id'],
            'subjects.*.faculty_id' => ['nullable', 'exists:faculty,id'],
            'subjects.*.schedule'   => ['nullable', 'string', 'max:100'],
            'subjects.*.room'       => ['nullable', 'string', 'max:50'],
        ]);

        $section->update($request->only([
            'name', 'grade_level_id', 'academic_year_id', 'adviser_id', 'max_students',
        ]));

        // Sync section subjects — remove old ones and create new
        $section->sectionSubjects()->delete();

        if ($request->has('subjects')) {
            foreach ($request->subjects as $subjectData) {
                if (!empty($subjectData['subject_id'])) {
                    SectionSubject::create([
                        'section_id' => $section->id,
                        'subject_id' => $subjectData['subject_id'],
                        'faculty_id' => $subjectData['faculty_id'] ?? null,
                        'schedule'   => $subjectData['schedule'] ?? null,
                        'room'       => $subjectData['room'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('registrar.sections.show', $section)
            ->with('success', 'Section updated successfully.');
    }

    public function destroy(Section $section): RedirectResponse
    {
        if ($section->students()->exists()) {
            return back()->with('error', 'Cannot delete section with assigned students.');
        }

        $section->sectionSubjects()->delete();
        $section->delete();

        return redirect()->route('registrar.sections.index')
            ->with('success', 'Section deleted successfully.');
    }
}

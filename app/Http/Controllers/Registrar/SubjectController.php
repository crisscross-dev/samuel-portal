<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function index(Request $request): View
    {
        $query = Subject::query()
            ->withCount(['programs', 'sectionSubjects', 'enrollmentSubjects'])
            ->orderBy('code');

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->search);

            $query->where(function ($builder) use ($search) {
                $builder->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $subjects = $query->paginate(15)->withQueryString();

        return view('registrar.subjects.index', compact('subjects'));
    }

    public function create(): View
    {
        return view('registrar.subjects.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:subjects,code'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'lecture_units' => ['required', 'integer', 'min:0', 'max:10'],
            'lab_units' => ['required', 'integer', 'min:0', 'max:10'],
            'is_active' => ['boolean'],
        ]);

        Subject::create($validated);

        return redirect()->route('registrar.subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    public function edit(Subject $subject): View
    {
        return view('registrar.subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('subjects', 'code')->ignore($subject->id)],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'lecture_units' => ['required', 'integer', 'min:0', 'max:10'],
            'lab_units' => ['required', 'integer', 'min:0', 'max:10'],
            'is_active' => ['boolean'],
        ]);

        $subject->update($validated);

        return redirect()->route('registrar.subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        if ($subject->programs()->exists() || $subject->sectionSubjects()->exists() || $subject->enrollmentSubjects()->exists()) {
            return back()->with('error', 'Cannot delete subject that is already used in curricula, sections, or enrollments.');
        }

        $subject->delete();

        return redirect()->route('registrar.subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }
}

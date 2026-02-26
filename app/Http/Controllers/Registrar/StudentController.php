<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registrar\StoreStudentRequest;
use App\Http\Requests\Registrar\UpdateStudentRequest;
use App\Models\Program;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Student::with(['user', 'program']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('program_id')) {
            $query->where('program_id', $request->program_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('student_id', 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($u) =>
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                  );
            });
        }

        $students = $query->latest()->paginate(15);
        $programs = Program::where('is_active', true)->get();

        return view('registrar.students.index', compact('students', 'programs'));
    }

    public function create(): View
    {
        $programs = Program::where('is_active', true)->get();
        return view('registrar.students.create', compact('programs'));
    }

    public function store(StoreStudentRequest $request): RedirectResponse
    {
        // Create user account
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password,
        ]);

        $user->assignRole('student');

        // Create student profile
        Student::create([
            'user_id'          => $user->id,
            'student_id'       => $request->student_id,
            'program_id'       => $request->program_id,
            'year_level'       => $request->year_level,
            'status'           => 'admitted',
            'date_of_birth'    => $request->date_of_birth,
            'gender'           => $request->gender,
            'address'          => $request->address,
            'contact_number'   => $request->contact_number,
            'guardian_name'    => $request->guardian_name,
            'guardian_contact' => $request->guardian_contact,
            'admission_date'   => now(),
        ]);

        return redirect()->route('registrar.students.index')
            ->with('success', 'Student record created successfully.');
    }

    public function show(Student $student): View
    {
        $student->load(['user', 'program', 'enrollments.semester', 'enrollments.payments']);
        return view('registrar.students.show', compact('student'));
    }

    public function edit(Student $student): View
    {
        $student->load('user');
        $programs = Program::where('is_active', true)->get();
        return view('registrar.students.edit', compact('student', 'programs'));
    }

    public function update(UpdateStudentRequest $request, Student $student): RedirectResponse
    {
        // Update user
        $student->user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        // Update student record
        $student->update($request->only([
            'student_id', 'program_id', 'year_level', 'status',
            'date_of_birth', 'gender', 'address', 'contact_number',
            'guardian_name', 'guardian_contact',
        ]));

        return redirect()->route('registrar.students.index')
            ->with('success', 'Student record updated successfully.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $student->user->delete();
        $student->delete();

        return redirect()->route('registrar.students.index')
            ->with('success', 'Student record deleted successfully.');
    }

    /**
     * Approve a student (set status to active).
     */
    public function approve(Student $student): RedirectResponse
    {
        if (!$student->isAdmitted()) {
            return back()->with('error', 'Only admitted students can be approved.');
        }

        $student->activate();

        return back()->with('success', 'Student status set to active.');
    }

    /**
     * Suspend a student.
     */
    public function reject(Student $student): RedirectResponse
    {
        $student->update(['status' => 'suspended']);

        return back()->with('success', 'Student has been suspended.');
    }
}

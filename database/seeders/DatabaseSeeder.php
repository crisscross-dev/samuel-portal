<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\GradeLevel;
use App\Models\Program;
use App\Models\Role;
use App\Models\Section;
use App\Models\SectionSubject;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Create Roles ──────────────────────────────────
        $roles = [
            ['name' => 'Admin',     'slug' => 'admin',     'description' => 'System Administrator'],
            ['name' => 'Registrar', 'slug' => 'registrar', 'description' => 'Registrar Staff'],
            ['name' => 'Faculty',   'slug' => 'faculty',   'description' => 'Faculty Member'],
            ['name' => 'Student',   'slug' => 'student',   'description' => 'Student'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['slug' => $role['slug']], $role);
        }

        // ── Create Admin User ─────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'      => 'System Administrator',
                'password'  => 'password',
                'is_active' => true,
            ]
        );
        $admin->assignRole('admin');

        // ── Create Registrar User ─────────────────────────
        $registrar = User::firstOrCreate(
            ['email' => 'registrar@gmail.com'],
            [
                'name'      => 'Registrar Staff',
                'password'  => 'password',
                'is_active' => true,
            ]
        );
        $registrar->assignRole('registrar');

        // ── Create Departments ────────────────────────────
        $jhsDept = Department::firstOrCreate(
            ['code' => 'JHS'],
            ['name' => 'Junior High School', 'description' => 'Grades 7–10', 'is_active' => true]
        );

        $shsDept = Department::firstOrCreate(
            ['code' => 'SHS'],
            ['name' => 'Senior High School', 'description' => 'Grades 11–12', 'is_active' => true]
        );

        // ── Create Grade Levels ───────────────────────────
        $gradeLevels = [];
        foreach (
            [
                ['department' => $jhsDept, 'name' => 'Grade 7',  'order' => 7],
                ['department' => $jhsDept, 'name' => 'Grade 8',  'order' => 8],
                ['department' => $jhsDept, 'name' => 'Grade 9',  'order' => 9],
                ['department' => $jhsDept, 'name' => 'Grade 10', 'order' => 10],
                ['department' => $shsDept, 'name' => 'Grade 11', 'order' => 11],
                ['department' => $shsDept, 'name' => 'Grade 12', 'order' => 12],
            ] as $gl
        ) {
            $gradeLevels[$gl['name']] = GradeLevel::firstOrCreate(
                ['department_id' => $gl['department']->id, 'name' => $gl['name']],
                ['level_order' => $gl['order'], 'is_active' => true]
            );
        }

        // ── Create Sample Faculty ─────────────────────────
        $facultyUser = User::firstOrCreate(
            ['email' => 'faculty@gail.com'],
            [
                'name'      => 'Prof. Juan Dela Cruz',
                'password'  => 'password',
                'is_active' => true,
            ]
        );
        $facultyUser->assignRole('faculty');
        $faculty = Faculty::firstOrCreate(
            ['user_id' => $facultyUser->id],
            [
                'employee_id'    => 'EMP-00001',
                'department_id'  => $jhsDept->id,
                'specialization' => 'Mathematics',
            ]
        );

        $facultyUser2 = User::firstOrCreate(
            ['email' => 'faculty2@gmail.com'],
            [
                'name'      => 'Prof. Ana Reyes',
                'password'  => 'password',
                'is_active' => true,
            ]
        );
        $facultyUser2->assignRole('faculty');
        $faculty2 = Faculty::firstOrCreate(
            ['user_id' => $facultyUser2->id],
            [
                'employee_id'    => 'EMP-00002',
                'department_id'  => $shsDept->id,
                'specialization' => 'Science',
            ]
        );

        // ── Assign Department Heads ───────────────────────
        $jhsDept->update(['head_faculty_id' => $faculty->id]);
        $shsDept->update(['head_faculty_id' => $faculty2->id]);

        // ── Create Sample Student ─────────────────────────
        $studentUser = User::firstOrCreate(
            ['email' => 'student@gmail.com'],
            [
                'name'      => 'Maria Santos',
                'password'  => 'password',
                'is_active' => true,
            ]
        );
        $studentUser->assignRole('student');

        // ── Create Programs ───────────────────────────────
        $programs = [
            // JHS
            ['code' => 'JHS-G7',  'name' => 'Grade 7',  'duration_years' => 1],
            ['code' => 'JHS-G8',  'name' => 'Grade 8',  'duration_years' => 1],
            ['code' => 'JHS-G9',  'name' => 'Grade 9',  'duration_years' => 1],
            ['code' => 'JHS-G10', 'name' => 'Grade 10', 'duration_years' => 1],
            // College
            ['code' => 'BSIT', 'name' => 'Bachelor of Science in Information Technology', 'duration_years' => 4],
            ['code' => 'BSCS', 'name' => 'Bachelor of Science in Computer Science',        'duration_years' => 4],
            ['code' => 'BSED', 'name' => 'Bachelor of Secondary Education',                'duration_years' => 4],
            ['code' => 'BSBA', 'name' => 'Bachelor of Science in Business Administration', 'duration_years' => 4],
        ];

        foreach ($programs as $prog) {
            Program::firstOrCreate(['code' => $prog['code']], $prog);
        }

        // ── Create Subjects ──────────────────────────────
        $subjects = [
            ['code' => 'IT101', 'name' => 'Introduction to Computing',     'lecture_units' => 3, 'lab_units' => 0],
            ['code' => 'IT102', 'name' => 'Computer Programming 1',         'lecture_units' => 2, 'lab_units' => 1],
            ['code' => 'IT103', 'name' => 'Data Structures & Algorithms',  'lecture_units' => 2, 'lab_units' => 1],
            ['code' => 'IT104', 'name' => 'Database Management Systems',   'lecture_units' => 2, 'lab_units' => 1],
            ['code' => 'IT105', 'name' => 'Web Development',               'lecture_units' => 2, 'lab_units' => 1],
            ['code' => 'GE101', 'name' => 'Understanding the Self',        'lecture_units' => 3, 'lab_units' => 0],
            ['code' => 'GE102', 'name' => 'Mathematics in the Modern World', 'lecture_units' => 3, 'lab_units' => 0],
            ['code' => 'GE103', 'name' => 'Purposive Communication',       'lecture_units' => 3, 'lab_units' => 0],
        ];

        foreach ($subjects as $subj) {
            Subject::firstOrCreate(['code' => $subj['code']], $subj);
        }

        // ── Attach Subjects to BSIT Program (Curriculum) ──
        $bsit = Program::where('code', 'BSIT')->first();
        if ($bsit) {
            $curriculum = [
                'IT101' => ['year_level' => 1, 'semester' => 1],
                'IT102' => ['year_level' => 1, 'semester' => 1],
                'GE101' => ['year_level' => 1, 'semester' => 1],
                'GE102' => ['year_level' => 1, 'semester' => 1],
                'IT103' => ['year_level' => 1, 'semester' => 2],
                'IT104' => ['year_level' => 1, 'semester' => 2],
                'IT105' => ['year_level' => 1, 'semester' => 2],
                'GE103' => ['year_level' => 1, 'semester' => 2],
            ];

            foreach ($curriculum as $code => $pivot) {
                $subject = Subject::where('code', $code)->first();
                if ($subject && !$bsit->subjects()->where('subject_id', $subject->id)->exists()) {
                    $bsit->subjects()->attach($subject->id, $pivot);
                }
            }
        }

        // ── Create Academic Year & Semesters ──────────────
        $ay = AcademicYear::firstOrCreate(
            ['name' => '2025-2026'],
            [
                'start_date' => '2025-08-01',
                'end_date'   => '2026-05-31',
                'is_active'  => true,
            ]
        );

        Semester::firstOrCreate(
            ['academic_year_id' => $ay->id, 'name' => '1st Semester'],
            [
                'start_date' => '2025-08-01',
                'end_date'   => '2025-12-20',
                'is_active'  => true,
            ]
        );

        Semester::firstOrCreate(
            ['academic_year_id' => $ay->id, 'name' => '2nd Semester'],
            [
                'start_date' => '2026-01-06',
                'end_date'   => '2026-05-31',
                'is_active'  => false,
            ]
        );

        // ── Create Student Record for sample student ──────
        $program = Program::where('code', 'BSIT')->first();
        if ($studentUser && $program) {
            \App\Models\Student::firstOrCreate(
                ['user_id' => $studentUser->id],
                [
                    'student_id'     => '2025-00001',
                    'program_id'     => $program->id,
                    'year_level'     => 1,
                    'status'         => 'active',
                    'admission_date' => '2025-08-01',
                ]
            );
        }

        // ── Create Sample Sections & Section-Subjects ─────
        $grade7 = $gradeLevels['Grade 7'];

        $sectionA = Section::firstOrCreate(
            ['name' => 'Rizal', 'grade_level_id' => $grade7->id, 'academic_year_id' => $ay->id],
            ['adviser_id' => $faculty->id, 'max_students' => 40]
        );

        // Assign subjects to section with faculty
        $subjectAssignments = [
            'IT101' => ['faculty' => $faculty,  'schedule' => 'MWF 8:00-9:00 AM',  'room' => 'Room 101'],
            'IT102' => ['faculty' => $faculty,  'schedule' => 'TTh 9:00-10:30 AM', 'room' => 'Lab 1'],
            'GE101' => ['faculty' => $faculty2, 'schedule' => 'MWF 10:00-11:00 AM', 'room' => 'Room 201'],
            'GE102' => ['faculty' => $faculty2, 'schedule' => 'TTh 1:00-2:30 PM',  'room' => 'Room 202'],
        ];

        foreach ($subjectAssignments as $code => $info) {
            $subject = Subject::where('code', $code)->first();
            if ($subject) {
                SectionSubject::firstOrCreate(
                    ['section_id' => $sectionA->id, 'subject_id' => $subject->id],
                    [
                        'faculty_id' => $info['faculty']->id,
                        'schedule'   => $info['schedule'],
                        'room'       => $info['room'],
                    ]
                );
            }
        }
    }
}

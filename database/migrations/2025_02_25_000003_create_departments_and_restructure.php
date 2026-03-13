<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ─── 1. Create departments table ──────────────────────────
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');                // Junior High School, Senior High School
            $table->string('code')->unique();      // JHS, SHS
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ─── 2. Create grade_levels table ─────────────────────────
        Schema::create('grade_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->string('name');                // Grade 7, Grade 11 STEM
            $table->unsignedTinyInteger('level_order')->default(1); // for sorting
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['department_id', 'level_order']);
        });

        // ─── 3. Clear dependent data (dev environment) ────────────
        // Sections are being fully restructured; clear related records
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            Schema::disableForeignKeyConstraints();
            DB::table('grades')->delete();
            DB::table('enrollment_subjects')->delete();
            DB::table('enrollments')->delete();
            DB::table('payments')->delete();
            DB::table('sections')->delete();
            Schema::enableForeignKeyConstraints();
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::table('grades')->truncate();
            DB::table('enrollment_subjects')->truncate();
            DB::table('enrollments')->truncate();
            DB::table('payments')->truncate();
            DB::table('sections')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        // ─── 4. Restructure sections table ────────────────────────
        Schema::table('sections', function (Blueprint $table) {
            // Drop old foreign keys
            $table->dropForeign(['semester_id']);
            $table->dropForeign(['program_id']);
            $table->dropForeign(['subject_id']);
            $table->dropForeign(['faculty_id']);

            // Drop old columns
            $table->dropColumn([
                'semester_id',
                'program_id',
                'subject_id',
                'faculty_id',
                'year_level',
                'schedule',
                'room',
            ]);
        });

        Schema::table('sections', function (Blueprint $table) {
            // Add new columns
            $table->foreignId('grade_level_id')->after('name')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_year_id')->after('grade_level_id')->constrained()->cascadeOnDelete();
            $table->foreignId('adviser_id')->nullable()->after('academic_year_id')->constrained('faculty')->nullOnDelete();

            // New indexes
            $table->index(['grade_level_id', 'academic_year_id']);
        });

        // ─── 5. Create section_subjects table ─────────────────────
        Schema::create('section_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('faculty_id')->nullable()->constrained('faculty')->nullOnDelete();
            $table->string('schedule')->nullable();   // MWF 9:00-10:00 AM
            $table->string('room')->nullable();        // Room 101
            $table->timestamps();
            $table->unique(['section_id', 'subject_id']);
            $table->index('faculty_id');
        });

        // ─── 6. Add department_id FK to faculty ───────────────────
        Schema::table('faculty', function (Blueprint $table) {
            $table->dropColumn('department'); // was a string
        });

        Schema::table('faculty', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->after('employee_id')->constrained()->nullOnDelete();
        });

        // ─── 7. Add section_id FK to students ─────────────────────
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('section_id')->nullable()->after('year_level')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        // Remove student section_id
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['section_id']);
            $table->dropColumn('section_id');
        });

        // Restore faculty department string
        Schema::table('faculty', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });

        Schema::table('faculty', function (Blueprint $table) {
            $table->string('department')->nullable()->after('employee_id');
        });

        // Drop section_subjects
        Schema::dropIfExists('section_subjects');

        // Restore sections structure
        Schema::table('sections', function (Blueprint $table) {
            $table->dropForeign(['grade_level_id']);
            $table->dropForeign(['academic_year_id']);
            $table->dropForeign(['adviser_id']);
            $table->dropIndex(['grade_level_id', 'academic_year_id']);
            $table->dropColumn(['grade_level_id', 'academic_year_id', 'adviser_id']);
        });

        Schema::table('sections', function (Blueprint $table) {
            $table->foreignId('semester_id')->after('name')->constrained()->cascadeOnDelete();
            $table->foreignId('program_id')->after('semester_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->after('program_id')->constrained()->cascadeOnDelete();
            $table->foreignId('faculty_id')->nullable()->after('subject_id')->constrained('faculty')->nullOnDelete();
            $table->unsignedTinyInteger('year_level')->after('faculty_id');
            $table->string('schedule')->nullable()->after('year_level');
            $table->string('room')->nullable()->after('schedule');
        });

        // Drop grade_levels and departments
        Schema::dropIfExists('grade_levels');
        Schema::dropIfExists('departments');
    }
};

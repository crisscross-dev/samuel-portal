<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();

            // ── Applicant Personal Information ──────────────
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('contact_number', 20)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('address')->nullable();

            // ── Academic Details ────────────────────────────
            $table->foreignId('program_applied_id')
                  ->constrained('programs')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();
            $table->unsignedTinyInteger('year_level')->default(1);

            // ── Guardian / Emergency Contact ────────────────
            $table->string('guardian_name')->nullable();
            $table->string('guardian_contact', 20)->nullable();

            // ── Document Upload (optional) ──────────────────
            $table->string('document_path')->nullable();

            // ── Review Workflow ─────────────────────────────
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('remarks')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            // ── Indexes ─────────────────────────────────────
            $table->index('status');
            $table->index('program_applied_id');
            $table->index('created_at');
        });

        // ── Update students.status enum to include 'admitted' & 'suspended' ──
        // Laravel doesn't natively support ALTER ENUM easily, so we use raw SQL
        \Illuminate\Support\Facades\DB::statement(
            "ALTER TABLE students MODIFY COLUMN status ENUM('applicant','admitted','active','inactive','suspended','graduated','dropped') DEFAULT 'admitted'"
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');

        \Illuminate\Support\Facades\DB::statement(
            "ALTER TABLE students MODIFY COLUMN status ENUM('applicant','active','inactive','graduated','dropped') DEFAULT 'applicant'"
        );
    }
};

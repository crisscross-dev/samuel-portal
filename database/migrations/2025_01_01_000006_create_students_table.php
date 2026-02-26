<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('student_id')->unique();   // School-specific ID, e.g., 2025-00001
            $table->foreignId('program_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedTinyInteger('year_level')->default(1);
            $table->enum('status', ['applicant', 'active', 'inactive', 'graduated', 'dropped'])->default('applicant');
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('address')->nullable();
            $table->string('contact_number', 20)->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_contact', 20)->nullable();
            $table->date('admission_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['program_id', 'year_level']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};

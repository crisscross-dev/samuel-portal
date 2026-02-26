<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['pending', 'assessed', 'enrolled', 'dropped', 'completed'])->default('pending');
            $table->unsignedSmallInteger('total_units')->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->timestamp('enrolled_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['student_id', 'semester_id']);
            $table->index(['semester_id', 'status']);
        });

        Schema::create('enrollment_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('section_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['enrollment_id', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollment_subjects');
        Schema::dropIfExists('enrollments');
    }
};

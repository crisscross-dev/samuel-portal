<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('faculty_id')->constrained('faculty')->cascadeOnDelete();
            $table->decimal('midterm_grade', 4, 2)->nullable();
            $table->decimal('final_grade', 4, 2)->nullable();
            $table->decimal('computed_grade', 4, 2)->nullable();
            $table->enum('remarks', ['passed', 'failed', 'incomplete', 'dropped', 'pending'])->default('pending');
            $table->boolean('is_finalized')->default(false);
            $table->timestamp('finalized_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['enrollment_subject_id', 'student_id']);
            $table->index(['student_id', 'is_finalized']);
            $table->index(['faculty_id', 'is_finalized']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};

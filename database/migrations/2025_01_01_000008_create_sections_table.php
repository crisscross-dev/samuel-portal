<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');                // BSIT-1A-IT101
            $table->foreignId('semester_id')->constrained()->cascadeOnDelete();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('faculty_id')->nullable()->constrained('faculty')->nullOnDelete();
            $table->unsignedTinyInteger('year_level');
            $table->string('schedule')->nullable();   // e.g., "MWF 9:00-10:00 AM"
            $table->string('room')->nullable();
            $table->unsignedSmallInteger('max_students')->default(40);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['semester_id', 'faculty_id']);
            $table->index(['semester_id', 'program_id', 'year_level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};

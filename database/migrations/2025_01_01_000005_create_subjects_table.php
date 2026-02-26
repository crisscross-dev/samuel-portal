<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();     // IT101, CS201, etc.
            $table->string('name');                // Programming Fundamentals
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('lecture_units')->default(3);
            $table->unsignedTinyInteger('lab_units')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Curriculum: which subjects belong to which program
        Schema::create('program_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('year_level');
            $table->unsignedTinyInteger('semester');  // 1, 2, 3 (summer)
            $table->timestamps();
            $table->unique(['program_id', 'subject_id']);
            $table->index(['program_id', 'year_level', 'semester']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_subject');
        Schema::dropIfExists('subjects');
    }
};

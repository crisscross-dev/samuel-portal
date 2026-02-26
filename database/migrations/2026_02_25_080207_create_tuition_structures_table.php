<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tuition_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->enum('pricing_type', ['flat', 'per_unit'])->default('per_unit');
            $table->foreignId('grade_level_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('program_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('flat_amount', 10, 2)->nullable();
            $table->decimal('lecture_rate', 10, 2)->nullable();
            $table->decimal('lab_rate', 10, 2)->nullable();
            $table->decimal('misc_fee', 10, 2)->default(0);
            $table->decimal('reg_fee', 10, 2)->default(0);
            $table->string('label')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Lock pricing per enrollment — nullable so old enrollments are unaffected
        Schema::table('enrollments', function (Blueprint $table) {
            $table->foreignId('tuition_structure_id')->nullable()
                ->after('semester_id')
                ->constrained('tuition_structures')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tuition_structure_id');
        });
        Schema::dropIfExists('tuition_structures');
    }
};

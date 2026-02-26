<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            // Drop old columns that are no longer needed
            $table->dropColumn(['midterm_grade', 'computed_grade']);
        });

        Schema::table('grades', function (Blueprint $table) {
            // Modify final_grade to decimal(5,2) for percentage grading (0-100)
            $table->decimal('final_grade', 5, 2)->nullable()->change();

            // Narrow the remarks enum to match the new spec
            // We'll handle this via application logic since MySQL enum changes are tricky
        });
    }

    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->decimal('midterm_grade', 4, 2)->nullable()->after('faculty_id');
            $table->decimal('computed_grade', 4, 2)->nullable()->after('final_grade');
        });

        Schema::table('grades', function (Blueprint $table) {
            $table->decimal('final_grade', 4, 2)->nullable()->change();
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->foreignId('interview_slot_id')
                ->nullable()
                ->after('interview_date')
                ->constrained('guidance_interview_slots')
                ->nullOnDelete();

            $table->unique('interview_slot_id', 'applications_interview_slot_unique_idx');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropUnique('applications_interview_slot_unique_idx');
            $table->dropConstrainedForeignId('interview_slot_id');
        });
    }
};

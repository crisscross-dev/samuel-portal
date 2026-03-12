<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->foreignId('exam_schedule_id')
                ->nullable()
                ->after('exam_schedule')
                ->constrained('exam_schedules')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['exam_schedule_id']);
            $table->dropColumn('exam_schedule_id');
        });
    }
};

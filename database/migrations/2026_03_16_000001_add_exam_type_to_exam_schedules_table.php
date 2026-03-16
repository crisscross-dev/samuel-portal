<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exam_schedules', function (Blueprint $table) {
            $table->enum('exam_type', ['jhs', 'shs'])
                ->default('jhs')
                ->after('time_slot');

            $table->index(['exam_type', 'is_active', 'exam_date'], 'exam_schedules_type_active_date_idx');
        });
    }

    public function down(): void
    {
        Schema::table('exam_schedules', function (Blueprint $table) {
            $table->dropIndex('exam_schedules_type_active_date_idx');
            $table->dropColumn('exam_type');
        });
    }
};

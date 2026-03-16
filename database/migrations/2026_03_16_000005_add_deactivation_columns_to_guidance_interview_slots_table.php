<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guidance_interview_slots', function (Blueprint $table) {
            $table->timestamp('deactivated_at')->nullable()->after('is_active');
            $table->string('deactivation_reason', 50)->nullable()->after('deactivated_at');
            $table->index(['is_active', 'interview_date'], 'guidance_slots_active_date_idx');
        });
    }

    public function down(): void
    {
        Schema::table('guidance_interview_slots', function (Blueprint $table) {
            $table->dropIndex('guidance_slots_active_date_idx');
            $table->dropColumn(['deactivated_at', 'deactivation_reason']);
        });
    }
};

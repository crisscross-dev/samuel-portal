<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->enum('account_status', ['pending', 'released'])
                ->default('pending')
                ->after('payment_status');
            $table->timestamp('account_released_at')
                ->nullable()
                ->after('account_status');
            $table->index(['workflow_stage', 'account_status'], 'applications_workflow_account_status_idx');
        });

        DB::table('applications')
            ->where('workflow_stage', 'cashier_payment')
            ->update(['account_status' => 'pending']);
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex('applications_workflow_account_status_idx');
            $table->dropColumn(['account_status', 'account_released_at']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('app_id', 25)->unique()->nullable()->after('id');
            $table->enum('payment_status', ['pending', 'paid'])->default('pending')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropUnique(['app_id']);
            $table->dropColumn(['app_id', 'payment_status']);
        });
    }
};

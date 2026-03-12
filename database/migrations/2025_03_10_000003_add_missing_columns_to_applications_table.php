<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('middle_name')->nullable()->after('first_name');
            $table->string('lrn', 12)->nullable()->after('last_name');
            $table->string('nationality', 100)->nullable()->after('gender');
            $table->string('religion', 100)->nullable()->after('nationality');
            $table->string('guardian_relationship', 100)->nullable()->after('guardian_contact');
            $table->string('elementary_school')->nullable()->after('guardian_relationship');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['middle_name', 'lrn', 'nationality', 'religion', 'guardian_relationship', 'elementary_school']);
        });
    }
};

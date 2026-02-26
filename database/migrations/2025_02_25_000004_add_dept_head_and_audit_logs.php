<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ─── 1. Add department head FK to departments ──────────
        Schema::table('departments', function (Blueprint $table) {
            $table->foreignId('head_faculty_id')
                  ->nullable()
                  ->after('is_active')
                  ->constrained('faculty')
                  ->nullOnDelete();
        });

        // ─── 2. Create grade audit log table ──────────────────
        Schema::create('grade_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('action');               // created, updated, finalized, reopened
            $table->decimal('old_grade', 5, 2)->nullable();
            $table->decimal('new_grade', 5, 2)->nullable();
            $table->string('old_remarks')->nullable();
            $table->string('new_remarks')->nullable();
            $table->text('notes')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamp('performed_at')->useCurrent();
            $table->timestamps();
            $table->index(['grade_id', 'performed_at']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grade_audit_logs');

        Schema::table('departments', function (Blueprint $table) {
            $table->dropForeign(['head_faculty_id']);
            $table->dropColumn('head_faculty_id');
        });
    }
};

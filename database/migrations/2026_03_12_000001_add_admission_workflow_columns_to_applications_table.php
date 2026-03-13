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
            $table->string('workflow_stage')->default('submitted')->after('status');
            $table->string('exam_result')->nullable()->after('workflow_stage');
            $table->boolean('is_active')->default(true)->after('payment_status');
            $table->text('exam_remarks')->nullable()->after('remarks');
            $table->foreignId('exam_result_recorded_by')->nullable()->after('exam_remarks')->constrained('users')->nullOnDelete();
            $table->timestamp('exam_result_recorded_at')->nullable()->after('exam_result_recorded_by');
            $table->timestamp('forwarded_to_guidance_at')->nullable()->after('exam_result_recorded_at');
            $table->date('interview_date')->nullable()->after('forwarded_to_guidance_at');
            $table->foreignId('guidance_user_id')->nullable()->after('interview_date')->constrained('users')->nullOnDelete();
            $table->text('guidance_remarks')->nullable()->after('guidance_user_id');
            $table->string('interview_form_token')->nullable()->unique()->after('guidance_remarks');
            $table->timestamp('interview_form_sent_at')->nullable()->after('interview_form_token');
            $table->timestamp('interview_form_submitted_at')->nullable()->after('interview_form_sent_at');

            $table->index(['workflow_stage', 'is_active']);
            $table->index('exam_result');
        });

        DB::table('applications')->where('status', 'pending')->update([
            'workflow_stage' => 'submitted',
            'is_active' => true,
        ]);

        DB::table('applications')->where('status', 'approved')->update([
            'workflow_stage' => 'exam_approved',
            'is_active' => true,
        ]);

        DB::table('applications')->where('status', 'rejected')->update([
            'workflow_stage' => 'rejected',
            'is_active' => false,
        ]);
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex(['workflow_stage', 'is_active']);
            $table->dropIndex(['exam_result']);
            $table->dropForeign(['exam_result_recorded_by']);
            $table->dropForeign(['guidance_user_id']);
            $table->dropColumn([
                'workflow_stage',
                'exam_result',
                'is_active',
                'exam_remarks',
                'exam_result_recorded_by',
                'exam_result_recorded_at',
                'forwarded_to_guidance_at',
                'interview_date',
                'guidance_user_id',
                'guidance_remarks',
                'interview_form_token',
                'interview_form_sent_at',
                'interview_form_submitted_at',
            ]);
        });
    }
};

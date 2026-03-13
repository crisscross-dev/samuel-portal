<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('interview_result')->nullable()->after('interview_form_submitted_at');
            $table->text('interview_remarks')->nullable()->after('interview_result');
            $table->foreignId('interview_evaluated_by')->nullable()->after('interview_remarks')->constrained('users')->nullOnDelete();
            $table->timestamp('interview_evaluated_at')->nullable()->after('interview_evaluated_by');
            $table->timestamp('returned_to_registrar_at')->nullable()->after('interview_evaluated_at');
            $table->boolean('is_archived')->default(false)->after('returned_to_registrar_at');
            $table->timestamp('archived_at')->nullable()->after('is_archived');
            $table->text('archive_reason')->nullable()->after('archived_at');
            $table->boolean('pre_enrolment_form_submitted')->default(false)->after('archive_reason');
            $table->boolean('student_health_form_submitted')->default(false)->after('pre_enrolment_form_submitted');
            $table->boolean('report_card_submitted')->default(false)->after('student_health_form_submitted');
            $table->boolean('id_picture_submitted')->default(false)->after('report_card_submitted');
            $table->text('requirements_remarks')->nullable()->after('id_picture_submitted');
            $table->foreignId('requirements_verified_by')->nullable()->after('requirements_remarks')->constrained('users')->nullOnDelete();
            $table->timestamp('requirements_verified_at')->nullable()->after('requirements_verified_by');
            $table->foreignId('enrollment_processed_by')->nullable()->after('requirements_verified_at')->constrained('users')->nullOnDelete();
            $table->timestamp('enrollment_processed_at')->nullable()->after('enrollment_processed_by');
            $table->timestamp('cashier_forwarded_at')->nullable()->after('enrollment_processed_at');

            $table->index(['workflow_stage', 'is_archived']);
            $table->index('interview_result');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex(['workflow_stage', 'is_archived']);
            $table->dropIndex(['interview_result']);
            $table->dropForeign(['interview_evaluated_by']);
            $table->dropForeign(['requirements_verified_by']);
            $table->dropForeign(['enrollment_processed_by']);
            $table->dropColumn([
                'interview_result',
                'interview_remarks',
                'interview_evaluated_by',
                'interview_evaluated_at',
                'returned_to_registrar_at',
                'is_archived',
                'archived_at',
                'archive_reason',
                'pre_enrolment_form_submitted',
                'student_health_form_submitted',
                'report_card_submitted',
                'id_picture_submitted',
                'requirements_remarks',
                'requirements_verified_by',
                'requirements_verified_at',
                'enrollment_processed_by',
                'enrollment_processed_at',
                'cashier_forwarded_at',
            ]);
        });
    }
};

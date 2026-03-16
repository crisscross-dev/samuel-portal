<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guidance_interview_slots', function (Blueprint $table) {
            $table->id();
            $table->enum('form_type', ['jhs', 'shs']);
            $table->date('interview_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['form_type', 'interview_date', 'is_active'], 'guidance_slots_type_date_active_idx');
            $table->unique(['form_type', 'interview_date', 'start_time', 'end_time'], 'guidance_slots_unique_window_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guidance_interview_slots');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('case_workflow_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('violation_record_id')->constrained('violation_records')->cascadeOnDelete();
            $table->foreignId('actor_id')->constrained('users')->restrictOnDelete()->comment('User who performed the action');
            $table->enum('action_type', [
                'Case Created',
                'Charge Filed',
                'Evidence Uploaded',
                'Student Answer Submitted',
                'Hearing Scheduled',
                'Hearing Held',
                'Committee Report Submitted',
                'Decision Rendered',
                'Sanction Applied',
                'Appeal Filed',
                'Case Resolved',
                'Record Accessed',
                'Parent Notified',
                'Status Changed',
            ]);
            $table->text('action_details')->nullable();
            $table->json('metadata')->nullable()->comment('Additional action data: old_value, new_value, etc.');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index('violation_record_id');
            $table->index('action_type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_workflow_logs');
    }
};

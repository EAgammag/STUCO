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
        Schema::table('violation_records', function (Blueprint $table) {
            // Case Tracking System - follows CSU Section G.4
            $table->string('case_tracking_number', 50)->unique()->after('id');

            // Progressive Offense Tracking
            $table->integer('offense_count')->default(1)->after('offense_id')->comment('1st, 2nd, or 3rd offense for this offense type');
            $table->string('applied_sanction')->nullable()->after('offense_count');

            // Due Process Timeline Tracking - CSU Section G.6, G.7, G.13
            $table->date('charge_filed_date')->nullable()->after('date_of_incident');
            $table->date('answer_deadline')->nullable()->after('charge_filed_date')->comment('5 class days from charge receipt');
            $table->date('hearing_scheduled_date')->nullable()->after('answer_deadline');
            $table->date('decision_deadline')->nullable()->after('hearing_scheduled_date')->comment('15 class days after final submission');
            $table->date('appeal_deadline')->nullable()->after('decision_deadline')->comment('10 class days from decision receipt');

            // Student Response
            $table->text('student_answer')->nullable()->after('incident_description');
            $table->date('student_answer_submitted_date')->nullable()->after('student_answer');

            // CSU Tribunal Assignment - Section G.1
            $table->boolean('assigned_to_sdt')->default(false)->after('reported_by');
            $table->json('sdt_members')->nullable()->after('assigned_to_sdt')->comment('Array of tribunal member IDs');

            // Investigation Type - Section G.22
            $table->enum('investigation_type', ['Tribunal', 'Summary', 'Dean Direct'])->default('Tribunal')->after('status');
            $table->foreignId('investigating_authority_id')->nullable()->constrained('users')->after('investigation_type');

            // Decision and Documentation
            $table->text('committee_report')->nullable()->after('resolution_notes');
            $table->text('final_decision')->nullable()->after('committee_report');
            $table->foreignId('decided_by')->nullable()->constrained('users')->after('final_decision');

            // Confidentiality Tracking - Section G.20
            $table->json('access_log')->nullable()->after('decided_by')->comment('Who accessed this confidential record');

            // Parent/Guardian Notification - Section G.21.2
            $table->boolean('parent_notified')->default(false)->after('access_log');
            $table->date('parent_notification_date')->nullable()->after('parent_notified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('violation_records', function (Blueprint $table) {
            $table->dropForeign(['investigating_authority_id']);
            $table->dropForeign(['decided_by']);

            $table->dropColumn([
                'case_tracking_number',
                'offense_count',
                'applied_sanction',
                'charge_filed_date',
                'answer_deadline',
                'hearing_scheduled_date',
                'decision_deadline',
                'appeal_deadline',
                'student_answer',
                'student_answer_submitted_date',
                'assigned_to_sdt',
                'sdt_members',
                'investigation_type',
                'investigating_authority_id',
                'committee_report',
                'final_decision',
                'decided_by',
                'access_log',
                'parent_notified',
                'parent_notification_date',
            ]);
        });
    }
};

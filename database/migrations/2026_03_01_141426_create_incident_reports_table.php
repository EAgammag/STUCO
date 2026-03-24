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
        Schema::create('incident_reports', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number')->unique(); // e.g., INC-2026-03-0145
            $table->foreignId('reporter_id')->constrained('users'); // The Staff/Faculty/Guard
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('offense_id')->constrained('offense_rules'); // Links to the CSU Manual matrix

            $table->enum('report_type', ['Quick Log', 'Formal Charge']);
            $table->text('description');
            $table->string('evidence_path')->nullable(); // For photos of vandalism, screenshots of cyberbullying, etc.

            $table->enum('status', ['Submitted', 'Under Review by OSDW', 'Resolved', 'Dismissed'])->default('Submitted');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_reports');
    }
};

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
        Schema::create('violation_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('offense_id')->constrained('offense_rules')->restrictOnDelete();
            $table->foreignId('reported_by')->constrained('users')->restrictOnDelete();
            $table->enum('status', ['Pending Review', 'Sanction Active', 'Resolved', 'Appealed'])->default('Pending Review');
            $table->text('incident_description');
            $table->date('date_of_incident');
            $table->date('resolution_date')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->timestamps();

            // Indexes for efficient student dashboard queries
            $table->index('student_id');
            $table->index('status');
            $table->index('date_of_incident');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('violation_records');
    }
};

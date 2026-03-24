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
        Schema::create('offense_rules', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique(); // e.g., "AC-001", "DC-015"
            $table->string('title');
            $table->text('description');
            $table->enum('category', ['Academic', 'Behavioral', 'Procedural', 'Safety', 'Technology']);
            $table->enum('severity_level', ['Minor', 'Moderate', 'Major', 'Severe']);
            $table->string('standard_sanction'); // e.g., "Written Warning", "Suspension"
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes for efficient queries
            $table->index('category');
            $table->index('severity_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offense_rules');
    }
};

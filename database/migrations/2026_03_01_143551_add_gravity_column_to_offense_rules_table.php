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
        Schema::table('offense_rules', function (Blueprint $table) {
            // Add gravity column to align with CSU Student Manual terminology
            // Minor Offenses vs Major Offenses classification
            $table->enum('gravity', ['minor', 'major', 'other'])
                ->default('minor')
                ->after('severity_level');

            // Add index for efficient filtering in Quick Log modal
            $table->index('gravity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offense_rules', function (Blueprint $table) {
            $table->dropIndex(['gravity']);
            $table->dropColumn('gravity');
        });
    }
};

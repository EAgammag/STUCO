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
            // Add separate columns for progressive sanctions
            $table->string('first_offense_sanction')->nullable()->after('standard_sanction');
            $table->string('second_offense_sanction')->nullable()->after('first_offense_sanction');
            $table->string('third_offense_sanction')->nullable()->after('second_offense_sanction');

            // Add legal reference field for policies like RA 10175
            $table->string('legal_reference')->nullable()->after('third_offense_sanction');

            // Track if offense requires tribunal vs summary investigation
            $table->boolean('requires_tribunal')->default(true)->after('legal_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offense_rules', function (Blueprint $table) {
            $table->dropColumn([
                'first_offense_sanction',
                'second_offense_sanction',
                'third_offense_sanction',
                'legal_reference',
                'requires_tribunal',
            ]);
        });
    }
};

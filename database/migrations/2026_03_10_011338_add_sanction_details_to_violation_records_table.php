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
            $table->dateTime('sanction_imposed_at')->nullable()->after('applied_sanction')->comment('When the sanction was decided/imposed');
            $table->dateTime('sanction_effective_at')->nullable()->after('sanction_imposed_at')->comment('When the sanction takes effect');
            $table->enum('settled_by', ['Dean', 'OSDW'])->nullable()->after('decided_by')->comment('Settled by Dean or OSDW');
            $table->text('action_taken')->nullable()->after('settled_by')->comment('Description of actions taken');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('violation_records', function (Blueprint $table) {
            $table->dropColumn([
                'sanction_imposed_at',
                'sanction_effective_at',
                'settled_by',
                'action_taken',
            ]);
        });
    }
};

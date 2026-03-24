<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('sections');
        Schema::dropIfExists('programs');
        Schema::dropIfExists('colleges');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tables permanently removed - no rollback
    }
};

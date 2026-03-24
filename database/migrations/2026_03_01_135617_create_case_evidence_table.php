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
        Schema::create('case_evidence', function (Blueprint $table) {
            $table->id();
            $table->foreignId('violation_record_id')->constrained('violation_records')->cascadeOnDelete();
            $table->foreignId('uploaded_by')->constrained('users')->restrictOnDelete();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type', 50)->comment('image, video, document, audio');
            $table->string('mime_type', 100);
            $table->bigInteger('file_size')->comment('Size in bytes');
            $table->text('description')->nullable();
            $table->enum('evidence_type', [
                'Photo Evidence',
                'Video Evidence',
                'Document',
                'Screenshot',
                'Audio Recording',
                'Written Statement',
                'Other',
            ])->default('Other');
            $table->timestamps();

            $table->index('violation_record_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_evidence');
    }
};

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseEvidence extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'case_evidence';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'violation_record_id',
        'uploaded_by',
        'file_name',
        'file_path',
        'file_type',
        'mime_type',
        'file_size',
        'description',
        'evidence_type',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
        ];
    }

    /**
     * Get the violation record associated with this evidence.
     */
    public function violationRecord(): BelongsTo
    {
        return $this->belongsTo(ViolationRecord::class);
    }

    /**
     * Get the user who uploaded this evidence.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the full URL to the evidence file.
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/'.$this->file_path);
    }

    /**
     * Get human-readable file size.
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2).' '.$units[$i];
    }
}

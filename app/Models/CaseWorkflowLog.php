<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseWorkflowLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'violation_record_id',
        'actor_id',
        'action_type',
        'action_details',
        'metadata',
        'ip_address',
        'user_agent',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    /**
     * Get the violation record associated with this log entry.
     */
    public function violationRecord(): BelongsTo
    {
        return $this->belongsTo(ViolationRecord::class);
    }

    /**
     * Get the user who performed this action.
     */
    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    /**
     * Create a log entry automatically capturing request context.
     */
    public static function logAction(
        int $violationRecordId,
        int $actorId,
        string $actionType,
        ?string $actionDetails = null,
        ?array $metadata = null
    ): self {
        return self::create([
            'violation_record_id' => $violationRecordId,
            'actor_id' => $actorId,
            'action_type' => $actionType,
            'action_details' => $actionDetails,
            'metadata' => $metadata,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}

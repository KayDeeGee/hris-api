<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveCredit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'leave_type_id',
        'total_credits',
        'used_credits',
        'year',
        'valid_from',
        'valid_until',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_until' => 'date',
        'is_active' => 'boolean',
        'total_credits' => 'integer',
        'used_credits' => 'integer',
        'year' => 'integer',
    ];

    /**
     * Get the user that owns the leave credit.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }

    /**
     * Scope to get active leave credits
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get leave credits for a specific year
     */
    public function scopeForYear($query, $year)
    {
        return $query->where('year', $year);
    }

    /**
     * Scope to get leave credits by type
     */
    public function scopeByType($query, $typeId)
    {
        return $query->where('leave_type_id', $typeId);
    }

    /**
     * Check if credits are currently valid
     */
    public function isCurrentlyValid(): bool
    {
        $today = now()->toDateString();
        return $this->valid_from <= $today && $this->valid_until >= $today;
    }

    /**
     * Get remaining credits (calculated attribute)
     */
    public function getRemainingCreditsAttribute(): int
    {
        return $this->total_credits - $this->used_credits;
    }

    /**
     * Use credits and update used amount
     */
    public function useCredits(int $credits): bool
    {
        if ($this->remaining_credits >= $credits) {
            $this->used_credits += $credits;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Restore credits (e.g., when leave is cancelled)
     */
    public function restoreCredits(int $credits): void
    {
        $this->used_credits = max(0, $this->used_credits - $credits);
        $this->save();
    }

    /**
     * Remove the boot method since we no longer need to calculate remaining_credits
     */
}

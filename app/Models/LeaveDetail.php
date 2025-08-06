<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveDetail extends Model
{
    protected $fillable = [
        'leave_request_id',
        'date',
        'length',
    ];

    protected $casts = [
        'date' => 'date',
        'length' => 'decimal:2', // 0.5 for half day, 1.0 for full day
    ];

    public function leaveRequest(): BelongsTo
    {
        return $this->belongsTo(LeaveRequest::class);
    }

    // Accessor to get human readable length
    public function getLengthTextAttribute()
    {
        return $this->length == 0.5 ? 'Half Day' : 'Full Day';
    }

    // Scope for specific date ranges
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    // Scope for full days only
    public function scopeFullDays($query)
    {
        return $query->where('length', 1);
    }

    // Scope for half days only
    public function scopeHalfDays($query)
    {
        return $query->where('length', 0.5);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'time_in',
        'time_out',
        'total_hours',
        'late_duration',
        'remarks',
    ];

    protected $casts = [
        'date' => 'date',
        'time_in' => 'datetime:H:i:s',
        'time_out' => 'datetime:H:i:s',
        'total_hours' => 'decimal:2',
        'late_duration' => 'decimal:2',
    ];

    /**
     * Get the employee that owns the attendance record.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Optional: Accessor to show formatted total hours
     */
    public function getFormattedTotalHoursAttribute(): string
    {
        return number_format($this->total_hours, 2) . ' hrs';
    }

    /**
     * Optional: Accessor to show formatted late duration
     */
    public function getFormattedLateDurationAttribute(): string
    {
        return $this->late_duration > 0
            ? number_format($this->late_duration, 2) . ' hrs late'
            : 'On time';
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'employee_number',
        'job_id',
        'qr_code_path',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attendanceLogs(): HasMany
    {
        return $this->hasMany(AttendanceLog::class);
    }

    public function jobPost(): BelongsTo
    {
        return $this->belongsTo(JobPost::class, 'job_id');
    }

    public function getRouteKeyName(): string
    {
        return 'employee_number';
    }

    public function personalDetail()
    {
        return $this->hasOne(EmployeePersonalDetail::class);
    }

    public function contacts()
    {
        return $this->hasMany(EmployeeContact::class);
    }

    public function addresses()
    {
        return $this->hasMany(EmployeeAddress::class);
    }

    public function emergencyContacts()
    {
        return $this->hasMany(EmployeeEmergencyContact::class);
    }

    public function movements()
    {
        return $this->hasMany(EmployeeMovement::class);
    }

    public function managers()
    {
        return $this->hasMany(EmployeeReportsTo::class, 'employee_id');
    }

    public function subordinates()
    {
        return $this->hasMany(EmployeeReportsTo::class, 'manager_id');
    }
}

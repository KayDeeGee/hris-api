<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    //
    protected $fillable = [
        'employee_id',
        'log_time',
        'device_name',
        'log_type',
        'log_method'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

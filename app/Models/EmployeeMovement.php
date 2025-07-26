<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeMovement extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'movement_type', // e.g., promotion, transfer, demotion
        'description',
        'effective_date',
        'from_job_id',
        'to_job_id',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function fromJob()
    {
        return $this->belongsTo(JobPost::class, 'from_job_id');
    }

    public function toJob()
    {
        return $this->belongsTo(JobPost::class, 'to_job_id');
    }
}

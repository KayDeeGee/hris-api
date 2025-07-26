<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeePersonalDetail extends Model
{
    //
    protected $fillable = [
        'employee_id',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'gender',
        'birthdate',
        'civil_status',
        'nationality',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

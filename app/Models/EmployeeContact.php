<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeContact extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'type',
        'value',
        'is_primary',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

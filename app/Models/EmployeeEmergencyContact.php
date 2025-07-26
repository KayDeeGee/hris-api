<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeEmergencyContact extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'name',
        'relationship',
        'contact_number',
        'email',
        'address',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

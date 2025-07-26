<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAddress extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'type', // e.g., present, permanent
        'region', // e.g., present, permanent
        'province',
        'city',
        'barangay',
        'street',
        'house_number',
        'zip_code',
        'country',
        'is_primary',
        // 'barangay',       <-- add this later in a migration file
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

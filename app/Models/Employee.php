<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //
    protected $fillable = [
        'user_id',
        'employee_number',
        'qr_code_path',
    ];
}

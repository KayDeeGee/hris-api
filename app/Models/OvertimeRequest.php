<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OvertimeRequest extends Model
{
    //
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'duration',
        'reason',
        'status',
        'reviewed_by',
        'reviewed_at',
    ];

    // Relations
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(Employee::class, 'reviewed_by');
    }
}

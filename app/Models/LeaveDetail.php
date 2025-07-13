<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveDetail extends Model
{
    //
    protected $fillable = [
        'date',
        'length',
    ];
    public function leaveRequest()
    {
        return $this->belongsTo(LeaveRequest::class);
    }
}

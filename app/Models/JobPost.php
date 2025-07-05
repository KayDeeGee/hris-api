<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPost extends Model
{
    /** @use HasFactory<\Database\Factories\JobPostFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'requirements',
        'salary',
        'qualifications',
        'status',
        'location',
        'created_by'
    ];

    protected $casts = [
        'requirements' => 'array',
        'qualifications' => 'array',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }
}

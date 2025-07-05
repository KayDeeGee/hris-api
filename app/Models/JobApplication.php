<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    /** @use HasFactory<\Database\Factories\JobApplicationFactory> */
    use HasFactory;

    protected $fillable = [
        'job_post_id',
        'user_id',
        'cover_letter',
        'resume_path',
        'status',
        'applied_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }
}

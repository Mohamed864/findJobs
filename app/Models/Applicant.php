<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Job;



class Applicant extends Model
{
    use HasFactory;

    protected $table = 'applicants';

    protected $fillable = [
        'full_name',
        'contact_phone',
        'contact_email',
        'message',
        'location',
        'resume_path',
        'user_id',
        'job_id'
    ];

    //relation to job
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    //relation to user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

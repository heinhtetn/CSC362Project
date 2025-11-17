<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'location', 'salary', 'admin_id'];

    public function savedByJobSeekers()
    {
        return $this->belongsToMany(
            JobSeeker::class,
            'saved_jobs',
            'job_id',
            'job_seeker_id'
        );
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}

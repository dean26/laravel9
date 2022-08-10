<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'deadline', 'author_id', 'content', 'user_id', 'job_id', 'completed',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function flags()
    {
        return $this->morphMany(Flag::class, 'owner')->orderBy('created_at', 'desc');
    }

    public function logs()
    {
        return $this->morphMany(Log::class, 'owner')->orderBy('created_at', 'desc');
    }
}

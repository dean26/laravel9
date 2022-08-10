<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date', 'end_date', 'expected_installation_date', 'user_id', 'customer_id', 'item_id', 'status',
    ];

    public function notes()
    {
        return $this->morphMany(Note::class, 'nottable')->orderBy('created_at', 'desc');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'owner')->orderBy('created_at', 'desc');
    }

    public function logs()
    {
        return $this->morphMany(Log::class, 'owner')->orderBy('created_at', 'desc');
    }

    public function flags()
    {
        return $this->morphMany(Flag::class, 'owner')->orderBy('created_at', 'desc');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'job_id', 'id')->orderBy('created_at', 'desc');
    }

    public function guides()
    {
        return $this->hasMany(Guide::class, 'job_id', 'id')->orderBy('created_at', 'desc');
    }
}

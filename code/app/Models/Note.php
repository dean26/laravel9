<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'content', 'user_id',
    ];

    protected $hidden = [
        'nottable_id', 'nottable_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function nottable()
    {
        return $this->morphTo();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnavailabilityTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'content', 'user_id', 'date_start', 'date_end'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

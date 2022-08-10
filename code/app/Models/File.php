<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'path', 'user_id', 'uploaded'
    ];

    protected $hidden = [
        'owner_id', 'owner_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function thumbnails()
    {
        return $this->hasMany(Thumbnail::class);
    }

    public function owner()
    {
        return $this->morphTo();
    }
}

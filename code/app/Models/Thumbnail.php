<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thumbnail extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'path', 'file_id',
    ];

    public function file()
    {
        return $this->belongsTo(File::class);
    }
}

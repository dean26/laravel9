<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Customer extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'name', 'builder', 'email', 'phone', 'adress',
    ];

    public function __toString()
    {
        return $this->name;
    }

    public function notes()
    {
        return $this->morphMany(Note::class, 'nottable')->orderBy('created_at', 'desc');
    }

    public function jobs()
    {
        return $this->hasMany(Job::class, 'customer_id', 'id');
    }

    public function getScoutKey()
    {
        return $this->id;
    }

    public function getScoutKeyName()
    {
        return 'id';
    }

    public function searchableAs()
    {
        return 'customers';
    }
}

<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class FlagService
{
    public function store(array $data, Model $model): Model
    {
        return $model->flags()->create([
            'user_id' => auth()->user()->id,
            'content' => $data['content'] ?? '',
        ]);
    }
}

<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class NoteService
{
    public function store(array $data, Model $model): Model
    {
        return $model->notes()->create([
            'user_id' => auth()->user()->id,
            'content' => $data['content'] ?? '',
        ]);
    }
}

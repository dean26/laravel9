<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class GuideService
{
    public function store(array $data, Model $model): Model
    {
        $data['author_id'] = auth()->user()->id;

        return $model->guides()->create($data);
    }
}

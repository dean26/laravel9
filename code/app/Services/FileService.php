<?php

namespace App\Services;

use App\Jobs\DigitalOceanUpload;
use App\Models\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class FileService
{
    public function store(FormRequest $request, Model $model): File
    {
        $file = $request->file('file');
        $name = Str::lower(Str::random(12));

        $extension = $file->getClientOriginalExtension();
        $filename = "{$name}.{$extension}";
        $fullpath = "{$name}/{$filename}";

        //delete if old file exits
        if ($model->files()->where('name', $name)->count() > 0) {
            $model->files()->where('name', $name)->delete();
        }

        //tmp upload to local file
        $file->storeAs(
            $name,
            $filename,
            'local'
        );

        //dispatch
        DigitalOceanUpload::dispatch($name, $filename);

        return $model->files()->create([
            'user_id' => auth()->user()->id,
            'path' => $fullpath,
            'name' => $name,
        ]);
    }
}

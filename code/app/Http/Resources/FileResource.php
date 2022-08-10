<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'path' => $this->path,
            //'local_url' => route('file_get', ['id' => $this->id]),
            'url' => Storage::url($this->path),
            'author_id' => $this->user_id,
            'author' => (string) $this->user,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'uploaded' => $this->uploaded,
            'thumbnails' => ThumbnailResource::collection($this->thumbnails),
        ];
    }
}

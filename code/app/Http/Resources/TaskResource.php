<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'content' => $this->content,
            'author_id' => $this->author_id,
            'author' => (string) $this->author,
            'deadline' => $this->deadline,
            'completed' => $this->completed,
            'user' => new UserResource($this->user),
            'job' => new JobResource($this->job),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'flags' => FlagResource::collection($this->flags),
        ];
    }
}

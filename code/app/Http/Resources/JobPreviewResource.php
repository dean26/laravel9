<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobPreviewResource extends JsonResource
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
            'user_id' => new UserResource($this->user),
            'customer' => new CustomerResource($this->customer),
            'item' => new ItemResource($this->item),
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'expected_installation_date' => $this->expected_installation_date,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'notes' => NoteResource::collection($this->notes),
            'files' => FileResource::collection($this->files),
            'logs' => LogResource::collection($this->logs),
            'tasks' => TaskSimpleResource::collection($this->tasks),
            'flags' => FlagResource::collection($this->flags),
            'guides' => GuideResource::collection($this->guides),
        ];
    }
}

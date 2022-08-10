<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $type = strtolower(class_basename($this->resource));
        $url = $type == 'user' ? 'users' : 'customers';

        return [
            'title' => $this->name,
            'subtitle' => $this->email,
            'id' => $this->id,
            'url' => "{$url}/{$this->id}",
            'type' => $type,
        ];
    }
}

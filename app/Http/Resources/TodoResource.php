<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TodoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'completed' => $this->resource->completed,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
            'completed_at' => $this->resource->completed_at,
        ];
    }
}

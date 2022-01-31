<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChapterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'slug' => $this->slug,
            'code' => $this->code,
            'description' => $this->description,
            'active' => $this->active,
            'state' => StateResource::make($this->whenLoaded('state')),
            'sections' => SectionResource::collection($this->whenLoaded('sections')),
        ];
    }
}

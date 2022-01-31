<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
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
            'url' => $this->url,
            'code' => $this->code,
            'description' => $this->description,
            'state' => StateResource::make($this->whenLoaded('state')),
            'chapter' => ChapterResource::make($this->whenLoaded('chapter')),
            'content' => $this->when(isset($this->include_content), $this->content),
        ];
    }
}

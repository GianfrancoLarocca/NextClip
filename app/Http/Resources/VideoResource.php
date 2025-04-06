<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Storage;

class VideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'visibility' => $this->visibility,
            'views' => $this->views,
            'duration' => $this->duration,
            'published_at' => $this->published_at,
            'video_url' => Storage::disk('public')->url($this->video_path),
            'thumbnail_url' => $this->thumbnail_path
                ? Storage::disk('public')->url($this->thumbnail_path)
                : null,
            'channel' => new ChannelResource($this->whenLoaded('channel')),
        ];
    }

}

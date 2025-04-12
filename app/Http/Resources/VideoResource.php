<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user = $request->user();

        \Log::debug('Utente autenticato in VideoResource', [
            'auth_user_id' => $user?->id,
            'like_ids' => $this->likes->pluck('id'),
        ]);        

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'visibility' => $this->visibility,
            'views' => $this->views,
            'duration' => $this->duration,
            'published_at' => $this->published_at,
            'video_url' => asset('storage/' . $this->video_path),
            'thumbnail_url' => $this->thumbnail_path ? asset('storage/' . $this->thumbnail_path) : null,
            'channel' => [
                'id' => $this->channel->id,
                'name' => $this->channel->name,
                'slug' => $this->channel->slug,
                'avatar' => $this->channel->avatar,
                'banner' => $this->channel->banner,
            ],
            'liked' => $user ? $this->likes->contains('id', $user->id) : false,
            'likes_count' => $this->likes->count(),
            'tags' => $this->tags->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                ];
            }),
        ];
    }
}

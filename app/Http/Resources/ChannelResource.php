<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChannelResource extends JsonResource
{
    /**
     * Trasforma il canale in una rappresentazione JSON.
     */
    public function toArray($request): array
    {

        $user = $request->user();
        
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'avatar' => $this->avatar ? asset('storage/' . $this->avatar) : null,
            'banner' => $this->banner ? asset('storage/' . $this->banner) : null,
            'subscribers_count' => $this->subscribers()->count(),
            'is_subscribed' => $user ? $this->subscribers->contains($user->id) : false,
        ];
    }
}

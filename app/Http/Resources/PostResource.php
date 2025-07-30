<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PostResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'excerpt' => Str::limit($this->body, 100),
            'author' => $this->user->name,
            'published_at' => $this->published_at?->toDateTimeString(),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
        ];
    }
}

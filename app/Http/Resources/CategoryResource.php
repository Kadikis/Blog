<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'posts_count' => $this->whenLoaded('posts', fn() => $this->posts->count()),
            'posts' => PostResource::collection($this->whenLoaded('posts')),
        ];
    }
}

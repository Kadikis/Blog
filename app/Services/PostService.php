<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class PostService
{
    private function validatePost(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'published_at' => 'date|nullable',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
        ]);
    }

    public function createPost(Request $request, int $userId): Post
    {
        $validated = $this->validatePost($request);

        $post = new Post();
        $post->title = $validated['title'];
        $post->body = $validated['body'];
        $post->published_at = $validated['published_at'] ?? null;
        $post->user_id = $userId;
        $post->save();

        // Sync categories if provided
        if (isset($validated['categories'])) {
            $post->categories()->sync($validated['categories']);
        }

        return $post;
    }

    public function updatePost(Request $request, int $postId): Post
    {
        $validated = $this->validatePost($request);

        $post = Post::find($postId);
        $post->title = $validated['title'];
        $post->body = $validated['body'];
        $post->published_at = $validated['published_at'] ?? null;
        $post->save();

        // Sync categories if provided
        if (isset($validated['categories'])) {
            $post->categories()->sync($validated['categories']);
        }

        return $post;
    }

    public function deletePost(int $postId): bool
    {
        $post = Post::find($postId);

        return $post ? $post->delete() : false;
    }
}

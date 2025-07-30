<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Post;
use Inertia\Response as InertiaResponse;
use Illuminate\Http\RedirectResponse;

class PostController extends Controller
{
    public function index(): InertiaResponse
    {
        return Inertia::render('Posts/Index', [
            'posts' => Post::with('categories')
                ->where('published_at', '<=', now())
                ->orderBy('published_at', 'desc')
                ->get()
                ->toResourceCollection(),
        ]);
    }

    public function show(string $id): InertiaResponse|RedirectResponse
    {
        $post = Post::with('categories')
            ->where('published_at', '<=', now())
            ->find($id);

        if (!$post) {
            return redirect()->route('posts.index');
        }

        return Inertia::render('Posts/Show', [
            'post' => $post->toResource(),
        ]);
    }
}

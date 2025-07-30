<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Post;
use Inertia\Response as InertiaResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request): InertiaResponse
    {
        $query = Post::with('categories', 'user')
            ->withCount('comments')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->orderBy('id', 'desc');

        if ($request->search_query) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search_query . '%')
                    ->orWhere('body', 'like', '%' . $request->search_query . '%');
            });
        }

        return Inertia::render('Posts/Index', [
            'posts' => $query->get()->toResourceCollection(),
            'searchQuery' => $request->search_query,
        ]);
    }

    public function show(string $id): InertiaResponse|RedirectResponse
    {
        $post = Post::with('categories', 'comments.user')
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

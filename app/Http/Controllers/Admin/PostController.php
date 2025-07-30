<?php

namespace App\Http\Controllers\Admin;

use Inertia\Response as InertiaResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Services\PostService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Post;
use App\Models\Category;

class PostController extends Controller
{
    public function __construct(private PostService $postService) {}

    public function index(): InertiaResponse
    {
        if (Auth::user()->cannot('viewAny', Post::class)) {
            abort(403);
        }

        return Inertia::render('Admin/Posts/Index', [
            'posts' => Post::with('categories')
                ->where('user_id', Auth::user()->id)
                ->orderBy('published_at', 'desc')
                ->get()
                ->toResourceCollection(),
        ]);
    }

    public function create(): InertiaResponse
    {
        if (Auth::user()->cannot('create', Post::class)) {
            abort(403);
        }

        return Inertia::render('Admin/Posts/Create', [
            'categories' => Category::all()->toResourceCollection(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->cannot('create', Post::class)) {
            abort(403);
        }

        $this->postService->createPost($request, Auth::user()->id);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post created successfully!');
    }

    public function show(string $id): InertiaResponse|RedirectResponse
    {
        $post = Post::with('categories')->find($id);

        if (Auth::user()->cannot('view', $post)) {
            abort(403);
        }

        return Inertia::render('Admin/Posts/Show', [
            'post' => $post->toResource(),
        ]);
    }

    public function edit(string $id): InertiaResponse|RedirectResponse
    {
        $post = Post::with('categories')->findOrFail($id);

        if (Auth::user()->cannot('update', $post)) {
            abort(403);
        }

        return Inertia::render('Admin/Posts/Edit', [
            'post' => $post->toResource(),
            'categories' => Category::all()->toResourceCollection(),
        ]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $post = Post::find($id);

        if (Auth::user()->cannot('update', $post)) {
            abort(403);
        }

        if (!$post) {
            return redirect()->route('admin.posts.index');
        }

        $this->postService->updatePost($request, $post->id);

        return redirect()->route('admin.posts.show', $post->id)
            ->with('success', 'Post updated successfully!');
    }

    public function destroy(string $id): RedirectResponse
    {
        $post = Post::find($id);

        if (Auth::user()->cannot('delete', $post)) {
            abort(403);
        }

        if (!$post) {
            return redirect()->route('admin.posts.index');
        }

        $this->postService->deletePost($post->id);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post deleted successfully!');
    }
}

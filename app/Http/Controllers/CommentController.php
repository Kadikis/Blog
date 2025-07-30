<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    public function store(Request $request, Post $post): RedirectResponse
    {
        if ($post->published_at > now()) {
            return redirect()->route('posts.index')->with('error', 'Something went wrong');
        }

        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $comment = new Comment();
        $comment->post_id = $post->id;
        $comment->user_id = request()->user()->id;
        $comment->content = $request->content;
        $comment->save();

        return redirect()->route('posts.show', $post->id);
    }

    public function destroy(Request $request, Comment $comment): RedirectResponse
    {
        if ($request->user()->cannot('delete', $comment)) {
            return redirect()->route('posts.show', $comment->post->id)->with('error', 'You are not authorized to delete this comment');
        }

        $comment->delete();

        return redirect()->route('posts.show', $comment->post->id);
    }
}

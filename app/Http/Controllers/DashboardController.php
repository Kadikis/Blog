<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPosts = Post::whereNotNull('published_at')->count();
        $totalComments = Comment::count();
        $totalUsers = User::where('is_admin', true)->count();

        $avgPostsPerUser = $totalUsers > 0 ? round($totalPosts / $totalUsers, 1) : 0;
        $avgCommentsPerPost = $totalPosts > 0 ? round($totalComments / $totalPosts, 1) : 0;

        $recentPosts = Post::where('published_at', '>=', now()->subDays(30))->count();
        $recentComments = Comment::where('created_at', '>=', now()->subDays(30))->count();

        $topAuthors = User::withCount('publishedPosts')
            ->where('is_admin', true)
            ->orderBy('published_posts_count', 'desc')
            ->take(5)
            ->get()
            ->map(function ($user) {
                return [
                    'name' => $user->name,
                    'posts_count' => $user->published_posts_count,
                ];
            });

        $mostCommentedPosts = Post::withCount('comments')
            ->with('user:id,name')
            ->orderBy('comments_count', 'desc')
            ->take(5)
            ->get()
            ->map(function ($post) {
                return [
                    'title' => $post->title,
                    'author' => $post->user->name,
                    'comments_count' => $post->comments_count,
                ];
            });

        return Inertia::render('Dashboard', [
            'stats' => [
                'totals' => [
                    'posts' => $totalPosts,
                    'comments' => $totalComments,
                    'users' => $totalUsers,
                ],
                'averages' => [
                    'posts_per_user' => $avgPostsPerUser,
                    'comments_per_post' => $avgCommentsPerPost,
                ],
                'recent' => [
                    'posts' => $recentPosts,
                    'comments' => $recentComments,
                ],
                'top_authors' => $topAuthors,
                'most_commented_posts' => $mostCommentedPosts,
            ],
        ]);
    }
}

<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    public function delete(User $user, Comment $comment): bool
    {
        //User can delete their own comment or if they are admin and the comment is on their post
        return $user->id === $comment->user_id || ($user->is_admin && $comment->post->user_id === $user->id);
    }
}

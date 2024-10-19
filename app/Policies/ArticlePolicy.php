<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    public function delete(User $user, Article $article): bool
    {
        return $user->id === $article->user_id;
    }

    public function edit(User $user, Article $article): bool
    {
        return $user->id === $article->user_id;
    }
}

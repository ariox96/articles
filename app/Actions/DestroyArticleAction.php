<?php

namespace App\Actions;

use App\Models\Article;

class DestroyArticleAction
{
    public function __invoke(Article $article): bool
    {
        return $article->delete();
    }
}

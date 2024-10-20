<?php

namespace App\Actions\Article;

use App\Models\Article;

class DestroyArticleAction
{
    public function __invoke(Article $article): bool
    {
        return $article->delete();
    }
}

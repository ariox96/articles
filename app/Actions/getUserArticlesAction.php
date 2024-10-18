<?php

namespace App\Actions;

use App\Models\Article;
use Illuminate\Support\Facades\Auth;

class getUserArticlesAction
{
    public function __invoke(int $userId)
    {
        return Article::getLatestUserArticles($userId);
    }
}

<?php

namespace App\Actions\Article;

use App\Models\Article;
use Illuminate\Pagination\LengthAwarePaginator;

class getUserArticlesAction
{
    public function __invoke(int $userId): LengthAwarePaginator
    {
        return Article::getLatestUserArticles($userId);
    }
}

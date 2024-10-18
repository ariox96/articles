<?php

namespace App\Actions;

use App\Models\Article;
use Illuminate\Pagination\LengthAwarePaginator;

class getPublishedArticlesAction
{
    public function __invoke(): LengthAwarePaginator
    {
        return Article::getPublishedArticles();
    }
}

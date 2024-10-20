<?php

namespace App\Actions\Article;

use App\Http\Requests\ArticleStoreRequest;
use App\Models\Article;

class InsertArticleAction
{
    /**
     * @throws \Exception
     */
    public function __invoke(ArticleStoreRequest $request): string
    {
        try {
            return Article::insertArticle($request);
        } catch (\Exception $e) {
            throw new \Exception('Failed to create article: '.$e->getMessage(), 500);
        }

    }
}

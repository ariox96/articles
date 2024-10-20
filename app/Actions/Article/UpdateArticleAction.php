<?php

namespace App\Actions\Article;

use App\Http\Requests\ArticleUpdateRequest;
use App\Models\Article;

class UpdateArticleAction
{
    /**
     * @throws \Exception
     */
    public function __invoke(ArticleUpdateRequest $request): string
    {
        try {
            return Article::updateArticle($request);
        } catch (\Exception $e) {
            throw new \Exception('Failed to update article: '.$e->getMessage(), 500);
        }

    }
}

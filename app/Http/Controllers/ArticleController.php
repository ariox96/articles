<?php

namespace App\Http\Controllers;

use App\Actions\Article\DestroyArticleAction;
use App\Actions\Article\getUserArticlesAction;
use App\Actions\Article\InsertArticleAction;
use App\Actions\Article\UpdateArticleAction;
use App\Http\Requests\ArticleStoreRequest;
use App\Http\Requests\ArticleUpdateRequest;
use App\Http\Resources\EditArticleResource;
use App\Http\Resources\ShowArticleResource;
use App\Models\Article;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function index(getUserArticlesAction $getUserArticlesAction): View
    {
        $articles = $getUserArticlesAction(Auth::id());

        return view('article.index', compact('articles'));
    }

    public function create(): View
    {
        return view('article.create');
    }

    /**
     * @throws Exception
     */
    public function store(ArticleStoreRequest $request, InsertArticleAction $insertArticleAction): RedirectResponse
    {
        $slug = $insertArticleAction($request);

        return redirect()->route('article.show', $slug);
    }

    /**
     * @throws AuthorizationException
     * @throws Exception
     */
    public function update(ArticleUpdateRequest $request, UpdateArticleAction $updateArticleAction): RedirectResponse
    {
        $article = Article::query()->find($request->id);
        $this->authorize('edit', $article);
        $slug = $updateArticleAction($request);

        return redirect()->route('article.show', $slug);
    }

    public function show(Article $article): View
    {
        $article->load('files', 'image');
        $article = (new ShowArticleResource($article))->toArray();

        return view('article.show', compact('article'));
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(Article $article): View
    {
        $this->authorize('edit', $article);
        $article = (new EditArticleResource($article))->toArray();

        return view('article.edit', compact('article'));
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Article $article, DestroyArticleAction $destroyArticleAction): JsonResponse
    {
        $this->authorize('delete', $article);
        $result = $destroyArticleAction($article);

        return response()->json([
            'status' => $result,
        ], $result ? 202 : 400);

    }
}

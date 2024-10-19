<?php

namespace App\Http\Controllers;

use App\Actions\DestroyArticleAction;
use App\Actions\getUserArticlesAction;
use App\Actions\InsertArticleAction;
use App\Enums\ArticleStatusEnum;
use App\Http\Requests\ArticleStoreRequest;
use App\Http\Requests\ArticleUpdateRequest;
use App\Http\Resources\EditArticleResource;
use App\Http\Resources\ShowArticleResource;
use App\Models\Article;
use App\Models\File;
use App\Models\Image;
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

    public function update(ArticleUpdateRequest $request): RedirectResponse
    {
        $articleItem = Article::query()->find($request->id);
        if (! $articleItem || $articleItem?->user_id != Auth::id()) {
            abort(404);
        }

        $image = null;
        $files = [];

        if ($request->hasFile('image')) {
            $image = $this->saveFile($request->file('image'), 'image');
        }
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $files[] = $this->saveFile($file, 'files');
            }
        }

        if ($image) {
            $image = Image::query()->create(['path' => $image]);
            $article['image_id'] = $image?->id;
        }

        $article['title'] = $request['title'];
        $article['content'] = $request['content'];
        $article['status'] = $request['status'];
        $article['author_name'] = $request['author_name'];
        if ($request['status'] == ArticleStatusEnum::PUBLISHED) {
            $article['published_at'] = now();
        }
        Article::query()->where('id', $request->id)->update($article);
        foreach ($files as $key => $file) {
            $files[$key] = [
                'article_id' => $request->id,
                'path' => $file,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        File::query()->insert($files);

        return redirect()->route('article.show', $articleItem->slug);
    }

    private function saveFile($request, $folderName): string
    {
        $id = Auth::id();
        $uniqid = uniqid();
        $path = "articleFiles/{$id}/$folderName";
        $fullPath = $path."/{$uniqid}.{$request->extension()}";
        $request->move(public_path($path), "{$uniqid}.{$request->extension()}");

        return $fullPath;
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

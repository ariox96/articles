<?php

namespace App\Http\Controllers;

use App\Actions\DestroyArticleAction;
use App\Actions\getUserArticlesAction;
use App\Enums\ArticleStatusEnum;
use App\Http\Requests\ArticleStoreRequest;
use App\Http\Requests\ArticleUpdateRequest;
use App\Http\Resources\EditArticleResource;
use App\Http\Resources\ShowArticleResource;
use App\Models\Article;
use App\Models\File;
use App\Models\Image;
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

    public function store(ArticleStoreRequest $request): RedirectResponse
    {
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
        }

        $article['image_id'] = $image?->id;
        $article['user_id'] = Auth::id();
        $article['title'] = $request['title'];
        $article['content'] = $request['content'];
        $article['status'] = $request['status'];
        $article['author_name'] = $request['author_name'];
        if ($request['status'] == ArticleStatusEnum::PUBLISHED) {
            $article['published_at'] = now();
        }
        $article = Article::query()->create($article);
        foreach ($files as $key => $file) {
            $files[$key] = [
                'article_id' => $article->id,
                'path' => $file,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        File::query()->insert($files);

        return redirect()->route('article.index');
    }

    public function update(ArticleUpdateRequest $request): RedirectResponse
    {
        $articleItem = Article::query()->find($request->id);
        if (!$articleItem || $articleItem?->user_id != Auth::id()) {
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
        $fullPath = $path . "/{$uniqid}.{$request->extension()}";
        $request->move(public_path($path), "{$uniqid}.{$request->extension()}");

        return $fullPath;
    }

    public function show(Article $article): View
    {
        $article->load('files', 'image');
        $article = (new ShowArticleResource($article))->toArray();
        return view('article.show', compact('article'));
    }

    public function edit(Article $article): View
    {
        if ($article->user_id != Auth::id()) {
            abort(404);
        }
        $article = (new EditArticleResource($article))->toArray();

        return view('article.edit', compact('article'));
    }

    public function destroy(Article $article, DestroyArticleAction $destroyArticleAction): JsonResponse
    {
        if ($article->user_id != Auth::id()) {
            abort(404);
        }

        $result = $destroyArticleAction($article);

        return response()->json([
            'status' => $result,
        ], $result ? 202 : 400);

    }
}

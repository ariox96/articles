<?php

namespace App\Http\Controllers;

use App\Enums\ArticleStatusEnum;
use App\Http\Requests\ArticleStoreRequest;
use App\Http\Requests\ArticleUpdateRequest;
use App\Models\Article;
use App\Models\File;
use App\Models\Image;
use GuzzleHttp\Psr7\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    //
    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function index(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $articles = Article::query()->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(8);
        return view('article.index', compact('articles'));
    }

    public function create()
    {
        return view('article.create');
    }

    /**
     * @param ArticleStoreRequest $request
     * @return RedirectResponse
     */
    public function store(ArticleStoreRequest $request): \Illuminate\Http\RedirectResponse
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
        if ($request['status'] == ArticleStatusEnum::STATUS_PUBLISHED->value) {
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


    /**
     * @param ArticleUpdateRequest $request
     * @return RedirectResponse
     */
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
        if ($request['status'] == ArticleStatusEnum::STATUS_PUBLISHED->value) {
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

    /**
     * @param $request
     * @param $folderName
     * @return string
     */
    private function saveFile($request, $folderName): string
    {
        $id = Auth::id();
        $uniqid = uniqid();
        $path = "articleFiles/{$id}/$folderName";
        $fullPath = $path . "/{$uniqid}.{$request->extension()}";
        $request->move(public_path($path), "{$uniqid}.{$request->extension()}");
        return $fullPath;
    }

    /**
     * @param Article $article
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function show(Article $article): \Illuminate\Foundation\Application|View|Factory|Application
    {
        $article->files;
        return view('article.show', compact('article'));
    }

    /**
     * @param Article $article
     * @return View|\Illuminate\Foundation\Application|Factory|Application
     */
    public function edit(Article $article): View|\Illuminate\Foundation\Application|Factory|Application
    {
        if ($article->user_id != Auth::id()) {
            abort(404);
        }
        return view('article.edit', compact('article'));
    }


    /**
     * @param Article $article
     * @return JsonResponse
     */
    public function destroy(Article $article): \Illuminate\Http\JsonResponse
    {
        if ($article->user_id != Auth::id()) {
            abort(404);
        }
        $article->delete();
        return response()->json([
            'status' => true,
        ], 202);
    }
}

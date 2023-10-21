<?php

namespace App\Http\Controllers;

use App\Enums\ArticleStatusEnum;
use App\Http\Requests\ArticleStoreRequest;
use App\Models\Article;
use App\Models\File;
use App\Models\Image;
use GuzzleHttp\Psr7\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
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

    public function store(ArticleStoreRequest $request)
    {
        $image = null;
        $files = [];
        $id = Auth::id();
        if ($request->hasFile('image')) {
            $uniqid = uniqid();
            $path = "articleFiles/{$id}/image";
            $fullPath = $path . "/{$uniqid}.{$request->file('image')->extension()}";
            $request->file('image')->move(public_path($path), "{$uniqid}.{$request->file('image')->extension()}");
            $image = $fullPath;
        }
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $uniqid = uniqid();
                $path = "articleFiles/{$id}/files";
                $fullPath = $path . "/{$uniqid}.{$file->extension()}";
                $file->move(public_path($path), "{$uniqid}.{$file->extension()}");
                $files[] = $fullPath;
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
     * @param Article $article
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function show(Article $article): \Illuminate\Foundation\Application|View|Factory|Application
    {
        $article->files;
        return view('article.show', compact('article'));
    }

    public function edit()
    {
    }

    public function update()
    {
    }

    /**
     * @param Article $article
     * @return JsonResponse
     */
    public function destroy(Article $article): \Illuminate\Http\JsonResponse
    {
        $article->delete();
        return response()->json([
            'status' => true,
        ], 202);
    }

    public function delete(Article $article)
    {
        dd($article);
    }
}

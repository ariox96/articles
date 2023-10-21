<?php

namespace App\Http\Controllers;

use App\Enums\ArticleStatusEnum;
use App\Http\Requests\ArticleStoreRequest;
use App\Models\Article;
use App\Models\File;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    //
    public function index()
    {
        $articles = Article::query()->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(8);
//        dd($articles);
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
        if ($request['status'] == ArticleStatusEnum::STATUS_PUBLISHED) {
            $article['published_at'] = now();
        }
        $article = Article::query()->create($article);
        foreach ($files as $key => $file) {
            $files[$key] = ['article_id' => $article->id, 'path' => $file];
        }
        File::query()->insert($files);
        return redirect()->route('article.index');
    }

    public function show()
    {
    }

    public function edit()
    {
    }

    public function update()
    {
    }

    public function destroy()
    {
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\View\View;

class MainController extends Controller
{
    public function __invoke(): View
    {
        $articles = Article::query()
            ->orderBy('published_at', 'desc')
            ->published()
            ->with('user')
            ->paginate(8);

        return view('index', compact('articles'));
    }
}

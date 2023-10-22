<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MainController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View|Application|Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function index(): \Illuminate\Contracts\View\View|Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $articles = Article::query()->orderBy('published_at','desc')->published()->with('user')->paginate(8);
        return view('index', compact('articles'));
    }
}

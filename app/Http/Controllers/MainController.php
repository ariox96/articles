<?php

namespace App\Http\Controllers;

use App\Actions\getPublishedArticlesAction;
use Illuminate\View\View;

class MainController extends Controller
{
    public function __invoke(getPublishedArticlesAction $getPublishedArticlesAction): View
    {
        $articles = $getPublishedArticlesAction();

        return view('index', compact('articles'));
    }
}

<?php

namespace App\DTOs;

use App\Http\Requests\ArticleStoreRequest;
use App\Http\Requests\ArticleUpdateRequest;
use Illuminate\Support\Carbon;

class ArticleDTO
{
    public ?int $id;

    public ?int $image_id;

    public int $user_id;

    public string $title;

    public string $content;

    public string $author_name;

    public int $status;

    public ?Carbon $published_at;

    public function __construct(ArticleStoreRequest|ArticleUpdateRequest $request)
    {
        $this->id = $request->id;
        $this->image_id = $request->image_id;
        $this->user_id = $request->user_id;
        $this->title = $request->title;
        $this->content = $request->content;
        $this->author_name = $request->author_name;
        $this->status = $request->status;
        $this->published_at = $request->published_at;
    }
}

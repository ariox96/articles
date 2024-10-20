<?php

namespace App\Http\Resources;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EditArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(?Request $request = null): array
    {
        /** @var Article $this */

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'status' => $this->status,
            'author_name' => $this->author_name,
            'published_at' => $this->published_at,
            'files' => $this->files->map(function ($file) {
                return [
                    'path' => $file->path,
                    'id' => $file->id,
                ];
            }),
            'image' => [
                'path' => $this->image?->path,
            ],
        ];
    }
}

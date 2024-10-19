<?php

namespace App\Http\Resources;

use App\Models\Article;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowArticleResource extends JsonResource
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
            'title' => $this->title,
            'content' => $this->content,
            'status' => $this->status,
            'author_name' => $this->author_name, // Assuming 'author' is a relationship
            'published_at' => $this->published_at,
            'files' => $this->files->map(function ($file) {
                return [
                    'path' => $file->path,  // Assuming 'path' is a field in 'files'
                ];
            }),
            'image' => [
                'path' => $this->image->path,  // Assuming 'image' is a relationship or attribute
            ],
        ];
    }
}

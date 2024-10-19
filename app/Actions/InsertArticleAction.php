<?php

namespace App\Actions;

use App\DTOs\ArticleDTO;
use App\Enums\ArticleStatusEnum;
use App\Http\Requests\ArticleStoreRequest;
use App\Models\Article;
use App\Models\File;
use App\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InsertArticleAction
{
    /**
     * @throws \Exception
     */
    public function __invoke(ArticleStoreRequest $request): string
    {
        /** @var Article $article */
        try {
            $files = $this->insertFilesToStorage($request);
            $image = $this->insertImageToStorage($request);

            $request->merge([
                'image_id' => $image?->id,
                'user_id' => Auth::id(),
                'published_at' => $request->input('status') == ArticleStatusEnum::PUBLISHED ? now() : null,
            ]);

            $articleDTO = new ArticleDTO($request);
            $article = Article::query()->create((array) $articleDTO);

            if (! empty($files)) {
                $fileData = collect($files)->map(fn ($file) => [
                    'article_id' => $article->id,
                    'path' => $file,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])->toArray();

                File::query()->insert($fileData);
            }

            return $article->slug;

        } catch (\Exception $e) {
            throw new \Exception('Failed to create article: '.$e->getMessage(), 500);
        }

    }

    public function insertFilesToStorage(ArticleStoreRequest $request): array
    {
        $files = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $files[] = $this->saveFile($file, 'files');
            }
        }

        return $files;
    }

    public function insertImageToStorage(ArticleStoreRequest $request): ?Model
    {
        return $request->hasFile('image')
            ? Image::query()->create(['path' => $this->saveFile($request->file('image'), 'image')])
            : null;
    }

    private function saveFile($request, $folderName): string
    {
        $id = Auth::id();
        $path = "articleFiles/{$id}/$folderName";
        $storedFilePath = $request->store("public/$path");

        return Storage::url($storedFilePath);
    }
}

<?php

namespace App\Actions\File;

use App\Http\Requests\ArticleStoreRequest;
use App\Http\Requests\ArticleUpdateRequest;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileAction
{
    public static function insertFilesToStorage(ArticleUpdateRequest|ArticleStoreRequest $request): array
    {
        $files = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $files[] = self::saveFile($file, 'files');
            }
        }

        return $files;
    }

    public static function insertImageToStorage(ArticleUpdateRequest|ArticleStoreRequest $request): ?Image
    {
        return $request->hasFile('image')
            ? Image::create(['path' => self::saveFile($request->file('image'), 'image')])
            : null;
    }

    private static function saveFile($request, $folderName): string
    {
        $id = Auth::id();
        $path = "articleFiles/{$id}/$folderName";
        $storedFilePath = $request->store("public/$path");

        return Storage::url($storedFilePath);
    }
}

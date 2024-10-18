<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    public function destroy(File $file): \Illuminate\Http\JsonResponse
    {
        if ($file->article->user_id != Auth::id()) {
            abort(404);
        }
        $file->delete();

        return response()->json([
            'status' => true,
        ], 202);
    }
}

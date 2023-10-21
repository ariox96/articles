<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    /**
     * @param File $file
     * @return JsonResponse
     */
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

<?php

namespace Database\Seeders;

use App\Models\Image;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Image::truncate();
        Image::query()->insert(['path' => 'storage/article/image/ArticleDefault.jpg']);
        Image::factory(50)->create();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}

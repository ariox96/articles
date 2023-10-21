<?php

namespace Tests\Unit;

use App\Models\Article;
use App\Models\File;
use App\Models\Image;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class FileTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_files_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('files', [
            'id', 'path', 'article_id', 'created_at', 'updated_at'
        ]));
    }


    /**
     * @return void
     */
    public function test_it_belongs_to_article()
    {
        $article = Article::factory()->create();
        $file = File::factory()->create(['article_id' => $article->id]);
        $fileArticle = $file->article;
        $this->assertInstanceOf(Article::class, $fileArticle);
        $this->assertEquals($article->id, $fileArticle->id);
    }
}

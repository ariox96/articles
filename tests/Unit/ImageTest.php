<?php

namespace Tests\Unit;

use App\Models\Article;
use App\Models\Image;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ImageTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_images_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('images', [
            'id', 'path', 'created_at', 'updated_at'
        ]));
    }


    /**
     * @return void
     */
    public function test_it_has_one_article()
    {
        $image = Image::factory()->create();
        $article = Article::factory()->create(['image_id' => $image->id]);
        $imageArticle = $image->article;
        $this->assertInstanceOf(Article::class, $imageArticle);
        $this->assertEquals($article->id, $imageArticle->id);
    }

}

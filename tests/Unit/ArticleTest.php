<?php

namespace Tests\Unit;

use App\Models\Article;
use App\Models\File;
use App\Models\Image;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_articles_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('articles', [
            'id', 'user_id', 'slug', 'title', 'content', 'image_id', 'status', 'published_at', 'updated_at', 'deleted_at', 'created_at'
        ]));
    }


    /**
     * @return void
     */
    public function test_it_belongs_to_user()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $user->id]);

        $articleUser = $article->user;

        $this->assertInstanceOf(User::class, $articleUser);
        $this->assertEquals($user->id, $articleUser->id);
    }

    /**
     * @return void
     */
    public function test_it_belongs_to_image()
    {
        $image = Image::factory()->create();
        $article = Article::factory()->create(['image_id' => $image->id]);
        $articleImage = $article->image;
        $this->assertInstanceOf(Image::class, $articleImage);
        $this->assertEquals($image->id, $articleImage->id);
    }

    /**
     * @return void
     */
    public function test_it_has_many_files()
    {
        $article = Article::factory()->create();
        $files = File::factory()->create(['article_id' => $article->id]);
        $articleFiles = $article->files;
        $this->assertInstanceOf(File::class, $articleFiles->first());
        $this->assertEquals($files->id, $articleFiles->first()->id);
    }
}

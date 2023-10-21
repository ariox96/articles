<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    public function test_show_the_articles_in_index_page()
    {
        $this->get(route('index'))
            ->assertStatus(200);
    }

    public function test_user_can_see_the_create_article_page()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('article.create'))
            ->assertSuccessful();
        $this->assertAuthenticated();
    }

    public function test_user_can_store_an_article()
    {
        $user = User::factory()->create();
        $image = UploadedFile::fake()->image("articleTestImage.jpg");
        $files = [
            UploadedFile::fake()->image("file1.jpg"),
            UploadedFile::fake()->image("file2.jpg"),
        ];

        $fields = ['title' => null, 'content' => null, 'status' => null];
        $attributes = array_intersect_key(Article::factory()->definition(), $fields);
        $attributes['image'] = $image;
        $attributes['files'] = $files;

        $this->actingAs($user)
            ->post(route('article.store'), $attributes)
            ->assertRedirect(route('article.index'));

        $this->assertAuthenticated();

        unset($attributes['image']);
        unset($attributes['files']);
        $createdArticle = Article::where(['content' => $attributes['content'], 'title' => $attributes['title']])->first();
        $this->assertNotNull($createdArticle);
        $this->assertNotNull($createdArticle->image);
        $this->assertNotNull($createdArticle->files);
    }

    public function test_user_can_show_the_edit_article_page()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(Article::class, $article);
        $this->actingAs($user)
            ->get(route('article.edit', $article->slug))
            ->assertSuccessful();
        $this->assertAuthenticated();
    }


    public function test_user_can_update_an_article()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $user->id]);

        $image = UploadedFile::fake()->image("articleTestImage.jpg");
        $files = [
            UploadedFile::fake()->image("file1.jpg"),
            UploadedFile::fake()->image("file2.jpg"),
        ];

        $fields = ['title' => null, 'content' => null, 'status' => null];
        $attributes = array_intersect_key(Article::factory()->definition(), $fields);
        $attributes['image'] = $image;
        $attributes['files'] = $files;
        $attributes['id'] = $article->id;

        $this->actingAs($user)
            ->put(route('article.update', $article->slug), $attributes)
            ->assertRedirect(route('article.show', $article->slug));

        $this->assertAuthenticated();
    }

    /**
     * @return void
     */
    public function test_user_can_destroy_an_article()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->delete(route('article.destroy', $article->slug))
            ->assertStatus(202);
        $this->assertAuthenticated();
    }
}

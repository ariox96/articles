<?php

namespace Tests\Unit;

use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_users_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('users', [
            'id', 'name', 'email', 'email_verified_at', 'password', 'remember_token', 'created_at', 'updated_at',
        ]), 1);
    }

    /**
     * @return void
     */
    public function test_it_has_many_articles()
    {
        $user = User::factory()->create();
        $articles = Article::factory()->create(['user_id' => $user->id]);
        $userArticles = $user->articles;
        $this->assertInstanceOf(Article::class, $userArticles->first());
        $this->assertEquals($articles->id, $userArticles->first()->id);
    }
}

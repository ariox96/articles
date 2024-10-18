<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $article = Article::query()->inRandomOrder()->first();
        if (! $article) {
            $article = Article::factory()->create();
        }

        return [
            'path' => fake()->imageUrl,
            'article_id' => $article->id,
            'created_at' => now(),
        ];
    }
}

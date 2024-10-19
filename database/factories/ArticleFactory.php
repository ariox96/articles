<?php

namespace Database\Factories;

use App\Enums\ArticleStatusEnum;
use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::query()->inRandomOrder()->first();
        if (! $user) {
            $user = User::factory()->create();
        }

        $image = Image::query()->inRandomOrder()->first();
        if (! $image) {
            $image = Image::factory()->create();
        }

        return [
            'user_id' => $user->id,
            'slug' => fake()->unique()->slug,
            'title' => fake()->realText(50),
            'content' => fake()->realText(3000),
            'image_id' => $image->id,
            'status' => array_rand(ArticleStatusEnum::options()),
            'author_name' => $user->name,
            'published_at' => now(),
            'created_at' => now(),
        ];
    }
}

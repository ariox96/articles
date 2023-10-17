<?php

namespace Database\Factories;

use App\Enums\ArticleStatusEnum;
use App\Models\Article;
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
        if (!$user) {
            $user = User::factory()->create();
        }

        $image = Image::query()->inRandomOrder()->first();
        if (!$image) {
            $image = Image::factory()->create();
        }

        $array = array_column(ArticleStatusEnum::cases(), 'value');
        return [
            'user_id' => $user->id,
            'slug' => fake()->unique()->slug,
            'title' => fake()->title,
            'content' => fake()->title,
            'image_id' => $image->id,
            'status' => $this->faker->randomElement($array),
            'published_at' => now(),
            'created_at' => now(),
        ];
    }
}

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
        $array = array_column(ArticleStatusEnum::cases(), 'value');
        return [
            'user_id' => User::query()->inRandomOrder()->first()->id,
            'slug' => fake()->unique()->slug,
            'title' => fake()->title,
            'content' => fake()->title,
            'image_id' => Image::query()->inRandomOrder()->first()->id,
            'status' => $this->faker->randomElement($array),
            'published_at' => now(),
            'created_at' => now(),
        ];
    }
}

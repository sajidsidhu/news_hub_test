<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Source;
use App\Services\NewsApiService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'content' => $this->faker->text(1000),
            'author' => $this->faker->name,
            'source_id' => Source::inRandomOrder()->first()?->source_id ?? 'unknown-source',
            'url' => $this->faker->unique()->url,
            'url_to_image' => $this->faker->imageUrl(),
            'category' => $this->faker->randomElement(NewsApiService::CATEGORIES),
            'published_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}

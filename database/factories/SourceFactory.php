<?php

namespace Database\Factories;

use App\Models\Source;
use App\Services\NewsApiService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Source>
 */
class SourceFactory extends Factory
{
    protected $model = Source::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'source_id' => $this->faker->unique()->lexify('source_????'),
            'name' => $this->faker->company,
            'description' => $this->faker->sentence,
            'url' => $this->faker->url,
            'category' => $this->faker->randomElement(NewsApiService::CATEGORIES),
            'language' => $this->faker->randomElement(['en','fr','de','es']),
            'country' => $this->faker->countryCode,
        ];
    }
}

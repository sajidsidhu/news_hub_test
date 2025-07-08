<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Source;
use App\Models\Article;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed sources
        Source::factory(10)->create();
        // Seed articles
        Article::factory(50)->create();
    }
}

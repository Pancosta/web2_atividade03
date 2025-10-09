<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Author;
use App\Models\Category;
use App\Models\Publisher;

class BookFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'author_id' => Author::inRandomOrder()->first()->id ?? Author::factory(),
            'category_id' => Category::inRandomOrder()->first()->id ?? 1,
            'publisher_id' => Publisher::inRandomOrder()->first()->id ?? Publisher::factory(),
            'published_year' => $this->faker->year(),
        ];
    }
}

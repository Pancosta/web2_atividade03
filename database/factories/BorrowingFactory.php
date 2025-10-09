<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Borrowing>
 */
class BorrowingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Borrowing::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // Cria um novo usuário ou usa um existente
            'book_id' => Book::inRandomOrder()->first()->id, // Seleciona um livro aleatório
            'borrowed_at' => $this->faker->dateTimeBetween('-1 month', 'now'), // Data de empréstimo
            'returned_at' => $this->faker->optional(0.7)->dateTimeBetween('now', '+1 month'), // 70% chance de já ter data de devolução
        ];
    }
}

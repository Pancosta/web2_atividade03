<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Book;

class AuthorPublisherBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Cria 50 autores
        Author::factory(50)->create();

        // Cria 20 editoras
        Publisher::factory(20)->create();

        // Cria 200 livros
        Book::factory(200)->create();
    }
}


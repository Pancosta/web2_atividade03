<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Borrowing;
use App\Models\Book;

class UserBorrowingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Pega todos os IDs de livros para evitar múltiplas consultas ao banco
        $bookIds = Book::pluck('id');

        if ($bookIds->isEmpty()) {
            $this->command->info('Nenhum livro encontrado. Pule o seeder de empréstimos.');
            return;
        }

        // Criar 10 usuários, e para cada um, criar entre 1 a 5 empréstimos
        User::factory(10)->create()->each(function ($user) use ($bookIds) {
            // Garante que um usuário não pegue o mesmo livro emprestado mais de uma vez
            $borrowedBookIds = $bookIds->random(min(rand(1, 5), $bookIds->count()))->unique();

            foreach ($borrowedBookIds as $bookId) {
                Borrowing::factory()->create([
                    'user_id' => $user->id,
                    'book_id' => $bookId,
                ]);
            }
        });
    }
}

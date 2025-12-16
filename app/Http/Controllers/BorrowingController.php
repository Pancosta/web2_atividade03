<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book;
use App\Models\Borrowing;

class BorrowingController extends Controller
{
    // Registrar empréstimo
    public function store(Request $request, Book $book)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // REGRA 1: livro não pode estar emprestado
        $emprestimoAbertoLivro = Borrowing::where('book_id', $book->id)
            ->whereNull('returned_at')
            ->exists();

        if ($emprestimoAbertoLivro) {
            return redirect()
                ->route('books.show', $book)
                ->with('error', 'Este livro já possui um empréstimo em aberto.');
        }

        // REGRA 2: usuário pode ter no máximo 5 empréstimos em aberto
        $emprestimosAbertosUsuario = Borrowing::where('user_id', $request->user_id)
            ->whereNull('returned_at')
            ->count();

        if ($emprestimosAbertosUsuario >= 5) {
            return redirect()
                ->route('books.show', $book)
                ->with('error', 'Este usuário já possui 5 empréstimos em aberto.');
        }

        // Registrar empréstimo
        Borrowing::create([
            'user_id' => $request->user_id,
            'book_id' => $book->id,
            'borrowed_at' => now(),
        ]);

        return redirect()
            ->route('books.show', $book)
            ->with('success', 'Empréstimo registrado com sucesso.');
    }

    // Registrar devolução
    public function returnBook(Borrowing $borrowing)
    {
        $borrowing->update([
            'returned_at' => now(),
        ]);

        return redirect()
            ->route('books.show', $borrowing->book_id)
            ->with('success', 'Devolução registrada com sucesso.');
    }

    // Histórico de empréstimos de um usuário
    public function userBorrowings(User $user)
    {
        $borrowings = $user->books()
            ->withPivot('borrowed_at', 'returned_at')
            ->get();

        return view('users.borrowings', compact('user', 'borrowings'));
    }
}

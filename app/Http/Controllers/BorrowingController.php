<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book;
use App\Models\Borrowing;

class BorrowingController extends Controller
{
    /**
     * Registrar empréstimo
     */
    public function store(Request $request, Book $book)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // 🔥 Verificar se já existe empréstimo em aberto deste livro
        $emprestimoAberto = Borrowing::where('book_id', $book->id)
            ->whereNull('returned_at')
            ->first();

        if ($emprestimoAberto) {
            return redirect()->route('books.show', $book)
                ->with('error', 'Este livro já está emprestado e ainda não foi devolvido.');
        }

        // Criar empréstimo
        Borrowing::create([
            'user_id'     => $request->user_id,
            'book_id'     => $book->id,
            'borrowed_at' => now(),
        ]);

        return redirect()->route('books.show', $book)
            ->with('success', 'Empréstimo registrado com sucesso.');
    }

    /**
     * Registrar devolução
     */
    public function returnBook(Borrowing $borrowing)
    {
        $borrowing->update([
            'returned_at' => now(),
        ]);

        return redirect()->route('books.show', $borrowing->book_id)
            ->with('success', 'Devolução registrada com sucesso.');
    }

    /**
     * Histórico de empréstimos de um usuário
     */
    public function userBorrowings(User $user)
    {
        $borrowings = $user->books()
            ->withPivot('borrowed_at', 'returned_at')
            ->get();

        return view('users.borrowings', compact('user', 'borrowings'));
    }
}

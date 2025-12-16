<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book;
use App\Models\Borrowing;
use Carbon\Carbon;

class BorrowingController extends Controller
{
    // REGISTRAR EMPRÉSTIMO
    public function store(Request $request, Book $book)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);

        // Bloqueia se usuário tem débito
        if ($user->debit > 0) {
            return redirect()
                ->back()
                ->with('error', 'Usuário possui débito pendente e não pode realizar empréstimos.');
        }

        // Bloqueia se livro já está emprestado
        $emprestimoAberto = Borrowing::where('book_id', $book->id)
            ->whereNull('returned_at')
            ->exists();

        if ($emprestimoAberto) {
            return redirect()
                ->back()
                ->with('error', 'Este livro já possui um empréstimo em aberto.');
        }

        // Limite de 5 livros por usuário
        $ativos = Borrowing::where('user_id', $user->id)
            ->whereNull('returned_at')
            ->count();

        if ($ativos >= 5) {
            return redirect()
                ->back()
                ->with('error', 'Este usuário já possui 5 empréstimos ativos.');
        }

        Borrowing::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrowed_at' => now(),
        ]);

        return redirect()
            ->route('books.show', $book)
            ->with('success', 'Empréstimo registrado com sucesso.');
    }

    // DEVOLVER LIVRO
    public function returnBook(Borrowing $borrowing)
    {
        $now = Carbon::now();
        $borrowedAt = Carbon::parse($borrowing->borrowed_at);
        $limitDate = $borrowedAt->copy()->addDays(15);

        $multa = 0;

        if ($now->greaterThan($limitDate)) {
            $diasAtraso = $limitDate->diffInDays($now);
            $multa = $diasAtraso * 0.50;

            $user = $borrowing->user;
            $user->debit += $multa;
            $user->save();
        }

        $borrowing->update([
            'returned_at' => $now,
        ]);

        return redirect()
            ->route('books.show', $borrowing->book_id)
            ->with(
                'success',
                $multa > 0
                    ? "Livro devolvido com atraso. Multa aplicada: R$ " . number_format($multa, 2, ',', '.')
                    : "Livro devolvido com sucesso."
            );
    }

    // HISTÓRICO DE EMPRÉSTIMOS
    public function userBorrowings(User $user)
    {
        $borrowings = $user->books()
            ->withPivot('borrowed_at', 'returned_at')
            ->get();

        return view('users.borrowings', compact('user', 'borrowings'));
    }
}

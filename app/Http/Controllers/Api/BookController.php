<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // LISTAR TODOS OS LIVROS
    public function index()
    {
        $books = Book::with(['author', 'publisher', 'category'])->get();

        return response()->json($books, 200);
    }

    // MOSTRAR UM LIVRO
    public function show($id)
    {
        $book = Book::with(['author', 'publisher', 'category'])->find($id);

        if (!$book) {
            return response()->json([
                'message' => 'Livro não encontrado'
            ], 404);
        }

        return response()->json($book, 200);
    }

    // CRIAR LIVRO
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'author_id'     => 'required|exists:authors,id',
            'publisher_id'  => 'required|exists:publishers,id',
            'category_id'   => 'required|exists:categories,id',
            'published_year'=> 'nullable|integer'
        ]);

        $book = Book::create($validated);

        return response()->json([
            'message' => 'Livro criado com sucesso',
            'book'    => $book
        ], 201);
    }

    // ATUALIZAR LIVRO
    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'message' => 'Livro não encontrado'
            ], 404);
        }

        $validated = $request->validate([
            'title'         => 'sometimes|required|string|max:255',
            'author_id'     => 'sometimes|required|exists:authors,id',
            'publisher_id'  => 'sometimes|required|exists:publishers,id',
            'category_id'   => 'sometimes|required|exists:categories,id',
            'published_year'=> 'nullable|integer'
        ]);

        $book->update($validated);

        return response()->json([
            'message' => 'Livro atualizado com sucesso',
            'book'    => $book
        ], 200);
    }

    // DELETAR LIVRO
    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'message' => 'Livro não encontrado'
            ], 404);
        }

        $book->delete();

        return response()->json([
            'message' => 'Livro removido com sucesso'
        ], 200);
    }
}

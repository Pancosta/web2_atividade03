<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Publisher;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    private function onlyAdminOrBibliotecario()
    {
        if (!auth()->check()) {
            return redirect('/')->with('error', 'Você precisa estar logado.');
        }

        $role = auth()->user()->role;

        if ($role !== 'admin' && $role !== 'bibliotecario') {
            return redirect('/')
                ->with('error', 'Você não tem permissão para realizar esta ação.');
        }

        return null;
    }

    // LISTAGEM DE LIVROS (index)
    public function index()
    {
        $books = Book::with(['author'])->paginate(20);
        return view('books.index', compact('books'));
    }

    // SHOW - Detalhes
    public function show(Book $book)
    {
        $book->load(['author', 'publisher', 'category']);
        $users = User::all();

        return view('books.show', compact('book', 'users'));
    }


    // CREATE (Input ID)
    public function createWithId()
    {
        if ($resp = $this->onlyAdminOrBibliotecario()) return $resp;
        return view('books.create-id');
    }

    // STORE (Input ID)
    public function storeWithId(Request $request)
    {
        if ($resp = $this->onlyAdminOrBibliotecario()) return $resp;

        $request->validate([
            'title' => 'required|string|max:255',
            'publisher_id' => 'required|exists:publishers,id',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        Book::create($request->all());

        return redirect()->route('books.index')->with('success', 'Livro criado com sucesso.');
    }

    // CREATE (Select)
    public function createWithSelect()
    {
        if ($resp = $this->onlyAdminOrBibliotecario()) return $resp;

        $publishers = Publisher::all();
        $authors = Author::all();
        $categories = Category::all();

        return view('books.create-select', compact('publishers', 'authors', 'categories'));
    }

    // STORE (Select)
    public function storeWithSelect(Request $request)
    {
        if ($resp = $this->onlyAdminOrBibliotecario()) return $resp;

        $request->validate([
            'title' => 'required|string|max:255',
            'publisher_id' => 'required|exists:publishers,id',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'cover' => 'nullable|image|max:2048'
        ]);

        $data = $request->only(['title', 'publisher_id', 'author_id', 'category_id']);

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('covers', 'public');
            $data['cover_image'] = $path;
        }

        Book::create($data);

        return redirect()->route('books.index')->with('success', 'Livro criado com sucesso.');
    }


    // EDIT
    public function edit(Book $book)
    {
        if ($resp = $this->onlyAdminOrBibliotecario()) return $resp;

        $publishers = Publisher::all();
        $authors = Author::all();
        $categories = Category::all();

        return view('books.edit', compact('book', 'publishers', 'authors', 'categories'));
    }

    // UPDATE
    public function update(Request $request, Book $book)
    {
        if ($resp = $this->onlyAdminOrBibliotecario()) return $resp;

        $request->validate([
            'title' => 'required|string|max:255',
            'publisher_id' => 'required|exists:publishers,id',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'cover' => 'nullable|image|max:2048'
        ]);

        $data = $request->only(['title', 'publisher_id', 'author_id', 'category_id']);

        if ($request->hasFile('cover')) {

            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }

            $path = $request->file('cover')->store('covers', 'public');
            $data['cover_image'] = $path;
        }

        $book->update($data);

        return redirect()->route('books.index')->with('success', 'Livro atualizado com sucesso.');
    }

    // DELETE
    public function destroy(Book $book)
    {
        if ($resp = $this->onlyAdminOrBibliotecario()) return $resp;

        if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Livro excluído com sucesso.');
    }
}

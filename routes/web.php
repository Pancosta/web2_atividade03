<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return view('home');
});
Route::get('/home', function () {
    return redirect('/');
})->name('home');

Auth::routes();

// ================================
// USUÁRIOS (somente admin fará controle no controller)
// ================================
Route::resource('users', UserController::class)
    ->except(['create', 'store', 'destroy']); 
// (cliente nunca cria usuários, e admin controla tudo via controller)


// ================================
// BOOKS
// ================================

// Criar via ID
Route::get('/books/create-id-number', [BookController::class, 'createWithId'])
    ->name('books.create.id');

Route::post('/books/create-id-number', [BookController::class, 'storeWithId'])
    ->name('books.store.id');

// Criar via selects
Route::get('/books/create-select', [BookController::class, 'createWithSelect'])
    ->name('books.create.select');

Route::post('/books/create-select', [BookController::class, 'storeWithSelect'])
    ->name('books.store.select');

// Resource (cliente só pode index/show, permissões dentro do controller)
Route::resource('books', BookController::class)
    ->except(['create', 'store']);


// ================================
// AUTORES, EDITORAS, CATEGORIAS
// ================================
Route::resource('authors', AuthorController::class);
Route::resource('publishers', PublisherController::class);
Route::resource('categories', CategoryController::class);


// ================================
// EMPRESTIMOS
// ================================

Route::post('/books/{book}/borrow', [BorrowingController::class, 'store'])
    ->name('books.borrow');

Route::get('/users/{user}/borrowings', [BorrowingController::class, 'userBorrowings'])
    ->name('users.borrowings');

Route::patch('/borrowings/{borrowing}/return', [BorrowingController::class, 'returnBook'])
    ->name('borrowings.return');

Route::get('/users-with-debit', [UserController::class, 'usersWithDebit'])
    ->name('users.debit');

Route::patch('/users/{user}/clear-debit', [UserController::class, 'clearDebit'])
    ->name('users.clearDebit');

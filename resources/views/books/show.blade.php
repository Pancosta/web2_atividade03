@extends('layouts.app')

@section('content')
<div class="container">

    {{-- MENSAGENS --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <h1 class="my-4">{{ $book->title }}</h1>

    <!-- Detalhes do livro -->
    <div class="card mb-4">
        <div class="card-header">Detalhes</div>
        <div class="card-body">
            <p><strong>Autor:</strong> {{ $book->author->name }}</p>
            <p><strong>Editora:</strong> {{ $book->publisher->name }}</p>
            <p><strong>Categoria:</strong> {{ $book->category->name }}</p>
            <p><strong>Capa:</strong></p>
            <div class="mb-3">
                <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : asset('images/cover-default.png') }}"
                    alt="Capa" style="max-height:250px;">
            </div>
        </div>
    </div>

    @php
        $emprestimoAberto = $book->users()->whereNull('returned_at')->first();
        $userRole = auth()->user()->role ?? 'cliente';
        $podeGerenciar = in_array($userRole, ['admin', 'bibliotecario']);
    @endphp

    <!-- Registrar Empréstimo -->
    @if($podeGerenciar)
        <div class="card mb-4">
            <div class="card-header">Registrar Empréstimo</div>
            <div class="card-body">

                @if($emprestimoAberto)
                    <div class="alert alert-warning">
                        ❗ Este livro já está emprestado para
                        <strong>{{ $emprestimoAberto->name }}</strong>.
                    </div>
                @else
                    <form action="{{ route('books.borrow', $book) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Usuário</label>
                            <select class="form-select" id="user_id" name="user_id" required>
                                <option value="" selected>Selecione um usuário</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-success">Registrar Empréstimo</button>
                    </form>
                @endif

            </div>
        </div>
    @endif

    <!-- Histórico -->
    <div class="card">
        <div class="card-header">Histórico de Empréstimos</div>
        <div class="card-body">
            @if($book->users->isEmpty())
                <p>Nenhum empréstimo registrado.</p>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Usuário</th>
                            <th>Data de Empréstimo</th>
                            <th>Data de Devolução</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($book->users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->pivot->borrowed_at }}</td>
                                <td>{{ $user->pivot->returned_at ?? 'Em Aberto' }}</td>
                                <td>
                                    @if($podeGerenciar && is_null($user->pivot->returned_at))
                                        <form action="{{ route('borrowings.return', $user->pivot->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-warning btn-sm">Devolver</button>
                                        </form>
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Usuários com Débito</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($users->isEmpty())
        <p>Nenhum usuário com débito.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Débito (R$)</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ number_format($user->debit, 2, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('users.clearDebit', $user) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-success btn-sm">
                                    Quitar Multa
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Lista de Usuários</h1>

    {{-- CLIENTE NÃO PODE VER LISTA DE USUÁRIOS --}}
    @if(auth()->user()->role === 'cliente')
        <div class="alert alert-danger">
            Você não tem permissão para visualizar a lista de usuários.
        </div>
        <a href="{{ url('/') }}" class="btn btn-secondary">Voltar</a>
        @return
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Papel</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge 
                            @if($user->role === 'admin') bg-danger
                            @elseif($user->role === 'bibliotecario') bg-primary
                            @else bg-secondary @endif">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td>
                        {{-- visualizar - todos podem --}}
                        <a href="{{ route('users.show', $user) }}" class="btn btn-info btn-sm">
                            <i class="bi bi-eye"></i> Visualizar
                        </a>

                        {{-- editar apenas admin --}}
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $users->links() }}
    </div>
</div>
@endsection

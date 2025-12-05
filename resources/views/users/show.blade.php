@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Detalhes do Usuário</h1>

    {{-- CLIENTE só pode visualizar ele mesmo --}}
    @if(auth()->user()->role === 'cliente' && auth()->id() !== $user->id)
        <div class="alert alert-danger">Você não tem permissão para ver outros usuários.</div>
        <a href="{{ url('/') }}" class="btn btn-secondary">Voltar</a>
        @return
    @endif

    <div class="card">
        <div class="card-header">
            {{ $user->name }}
        </div>
        <div class="card-body">
            <p><strong>Email:</strong> {{ $user->email }}</p>

            <p>
                <strong>Papel:</strong> 
                <span class="badge 
                    @if($user->role === 'admin') bg-danger
                    @elseif($user->role === 'bibliotecario') bg-primary
                    @else bg-secondary
                    @endif">
                    {{ ucfirst($user->role) }}
                </span>
            </p>
        </div>
    </div>

    <a href="{{ route('users.index') }}" class="btn btn-secondary mt-3">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
</div>
@endsection

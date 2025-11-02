<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // LISTAGEM DE USUÁRIOS
    public function index()
    {
        $users = User::paginate(10); // Paginação de 10 usuários por página
        return view('users.index', compact('users'));
    }

    // EXIBIR DETALHES DO USUÁRIO
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    // FORMULÁRIO DE EDIÇÃO
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // SALVAR ALTERAÇÕES
    public function update(Request $request, User $user)
    {
        $user->update($request->only('name', 'email'));

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso.');
    }
}

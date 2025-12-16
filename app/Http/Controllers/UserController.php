<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    private function onlyAdmin()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect('/')
                ->with('error', 'Você não tem permissão para acessar essa página.');
        }
        return null;
    }

    // LISTAGEM DE USUÁRIOS
    public function index()
    {
        if ($resp = $this->onlyAdmin())
            return $resp;

        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    // EXIBIR DETALHES DO USUÁRIO
    public function show(User $user)
    {
        if ($resp = $this->onlyAdmin())
            return $resp;

        return view('users.show', compact('user'));
    }

    // FORMULÁRIO DE EDIÇÃO
    public function edit(User $user)
    {
        if ($resp = $this->onlyAdmin())
            return $resp;

        $roles = ['admin', 'bibliotecario', 'cliente'];
        return view('users.edit', compact('user', 'roles'));
    }

    // SALVAR ALTERAÇÕES
    public function update(Request $request, User $user)
    {
        if ($resp = $this->onlyAdmin())
            return $resp;

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required'
        ]);

        // Impede admin de remover o próprio papel
        if ($user->id == auth()->id() && $request->role !== 'admin') {
            return redirect()->back()
                ->with('error', 'Você não pode remover seu próprio papel de admin.');
        }

        // Impede remover o último admin
        if ($user->role === 'admin' && $request->role !== 'admin') {
            $countAdmins = User::where('role', 'admin')->count();
            if ($countAdmins <= 1) {
                return redirect()->back()
                    ->with('error', 'O sistema precisa ter pelo menos 1 administrador.');
            }
        }

        // Atualiza dados
        $user->update($request->only('name', 'email', 'role'));

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso.');
    }
    public function usersWithDebit()
    {
        $users = User::where('debit', '>', 0)->get();
        return view('users.debit', compact('users'));
    }

    public function clearDebit(User $user)
    {
        $user->update(['debit' => 0]);

        return redirect()
            ->route('users.debit')
            ->with('success', 'Débito zerado com sucesso.');
    }

}

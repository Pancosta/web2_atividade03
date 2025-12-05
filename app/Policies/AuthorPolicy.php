<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Author;

class AuthorPolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Author $author)
    {
        return true;
    }

    public function create(User $user)
    {
        return in_array($user->role, ['admin', 'bibliotecario']);
    }

    public function update(User $user, Author $author)
    {
        return in_array($user->role, ['admin', 'bibliotecario']);
    }

    public function delete(User $user, Author $author)
    {
        return in_array($user->role, ['admin', 'bibliotecario']);
    }
}

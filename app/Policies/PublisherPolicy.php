<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Publisher;

class PublisherPolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Publisher $publisher)
    {
        return true;
    }

    public function create(User $user)
    {
        return in_array($user->role, ['admin', 'bibliotecario']);
    }

    public function update(User $user, Publisher $publisher)
    {
        return in_array($user->role, ['admin', 'bibliotecario']);
    }

    public function delete(User $user, Publisher $publisher)
    {
        return in_array($user->role, ['admin', 'bibliotecario']);
    }
}

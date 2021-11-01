<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public User $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function find(int $id): ?User
    {
        return $this->user->find($id);
    }

    public function update(User $user, array $attributes)
    {
        $user->fill($attributes);
        $user->save();
    }
}
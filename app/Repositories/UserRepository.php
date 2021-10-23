<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserRepository
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function find(int $id): ?User
    {
        return $this->user->find($id);
    }
}
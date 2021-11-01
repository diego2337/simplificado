<?php

namespace App\Repositories;

use App\Models\Role;

class RoleRepository
{
    private Role $role;

    public function __construct()
    {
        $this->role = new Role();
    }

    public function find(int $id): ?Role
    {
        return $this->role->find($id);
    }
}

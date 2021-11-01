<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AddRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::factory([
            'name' => 'CUSTOMER',
        ])
        ->count(1)
        ->create();

        Role::factory([
            'name' => 'SELLER',
        ])
        ->count(1)
        ->create();
    }
}

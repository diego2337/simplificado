<?php

namespace Database\Seeders;

use App\Models\Role;
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
        Role::factory(
            [
                'name' => 'CUSTOMER',
            ],
            [
                'name' => 'SELLER',
            ]
        )->create();
    }
}

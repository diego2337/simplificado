<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AddUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(
            [
                'name' => 'Nome 1',
                'email' => 'email1@test.com',
                'role_id' => 1,
                'cpf_cnpj' => '79831098064',
                'password' => '95685c580abde53d7a07587d9ee04dd4',
                'balance' => 0.00,
            ],
            [
                'name' => 'Nome 2',
                'email' => 'email2@test.com',
                'role_id' => 2,
                'cpf_cnpj' => '27435531000163',
                'password' => '000bb631f17d92a7892e938fc47c18af',
                'balance' => 0.00
            ],
            [
                'name' => 'Nome 3',
                'email' => 'email3@test.com',
                'role_id' => 1,
                'cpf_cnpj' => '82373134020',
                'password' => 'be4a6ffcaca5c61629fc603668dc050b',
                'balance' => 4.50,
            ],
            [
                'name' => 'Nome 4',
                'email' => 'email4@test.com',
                'role_id' => 2,
                'cpf_cnpj' => '30315160000190',
                'password' => 'cdc35dac8aae8b1365c9a5af85d49d83',
                'balance' => 22.30,
            ]
        )->create();
    }
}

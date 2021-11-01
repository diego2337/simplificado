<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'cpf_cnpj' => $this->faker->creditCardNumber,
            'email' => $this->faker->unique()->safeEmail(),
            'role_id' => $this->faker->numberBetween(1,2),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'balance' => $this->faker->randomFloat(min: 0.00, max: 100.37),
            'remember_token' => Str::random(10),
        ];
    }
}

<?php

namespace Tests\Unit\Repositories;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private UserRepository $userRepository;
    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = new UserRepository();
    }

    /** @test */
    public function should_return_an_instance_of_user_repository()
    {
        $this->userRepository = new UserRepository();
        $className = get_class($this->userRepository);
        $this->assertEquals(UserRepository::class, $className);
    }

    /** @test */
    public function should_return_user_by_id()
    {
        $idToSearch = 1;

        User::factory(
            id: $idToSearch,
            name: 'Test name',
            cpf_cnpj: 4485080168508784,
            email: 'qgusikowski@example.net',
            role_id: 1,
            password: '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            balance: 100.0,
            remember_token: 'cUNVDBSJ7x',
        )->create();

        $foundUser = $this->userRepository->find($idToSearch);
        $this->assertEquals($idToSearch, $foundUser->getAttribute('id'));
    }

    /** @test */
    public function should_return_updated_user()
    {
        $user = User::factory()->create();
        $updateAttributes = [
            'email' => 'updatedEmail@email.com',
        ];
        $this->userRepository->update($user, $updateAttributes);
        $this->assertDatabaseHas(User::class, [
            'email' => 'updatedEmail@email.com'
        ]);
    }
}

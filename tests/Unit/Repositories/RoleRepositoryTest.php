<?php

namespace Tests\Unit\Repositories;

use App\Models\Role;
use App\Repositories\RoleRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private RoleRepository $roleRepository;
    public function setUp(): void
    {
        parent::setUp();
        $this->roleRepository = new RoleRepository();
    }

    /** @test */
    public function should_return_an_instance_of_role_repository()
    {
        $this->roleRepository = new RoleRepository();
        $className = get_class($this->roleRepository);
        $this->assertEquals(RoleRepository::class, $className);
    }

    /** @test */
    public function should_return_role_by_id()
    {
        $idToSearch = 1;

        Role::factory(
            id: $idToSearch,
            name: 'Test name',
        )->create();

        $foundRole = $this->roleRepository->find($idToSearch);
        $this->assertEquals($idToSearch, $foundRole->getAttribute('id'));
    }
}

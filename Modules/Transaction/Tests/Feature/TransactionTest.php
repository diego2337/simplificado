<?php

namespace Modules\Transaction\Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use Modules\Transaction\Exceptions\TransactionException;
use Modules\Transaction\Http\Clients\REST\AuthorizerClient;
use Modules\Transaction\Http\Clients\REST\NotifierClient;
use Modules\Transaction\Services\TransactionService;
use Modules\Transaction\Tests\Helpers\CreateTestTransactionDTO;
use Modules\Transaction\Tests\Helpers\CreateTransactionSuccess;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionTest extends TestCase
{
    use RefreshDatabase, CreateTransactionSuccess, CreateTestTransactionDTO;

    private TransactionService $transactionService;
    public function setUp(): void
    {
        parent::setUp();
        $this->transactionService = new TransactionService(
            new UserRepository(),
            new RoleRepository(),
            new AuthorizerClient(),
            new NotifierClient()
        );

    }

    /** @test */
    public function should_return_200_and_make_transaction()
    {
        $this->createTransactionSuccess($this->transactionService);
        $testTransactionDto = $this->createTestTransactionDTO();
        $response = $this->postJson(
            '/transaction',
            $testTransactionDto->toJson(),
        );
        $response
            ->assertStatus(ResponseAlias::HTTP_OK)
            ->assertJson([
                'message' => 'Transaction successful',
            ]);
    }

    /** @test */
    public function should_not_find_payer_and_return_400()
    {
        $testTransactionDto = $this->createTestTransactionDTO();
        $response = $this->postJson(
            '/transaction',
            $testTransactionDto->toJson(),
        );
        $response
            ->assertStatus(ResponseAlias::HTTP_BAD_REQUEST)
            ->assertJson([
                'error' => trans('transaction::exceptions.' . TransactionException::USER_NOT_FOUND),
            ]);
    }

    /** @test */
    public function should_not_find_payee_and_return_400()
    {
        $testTransactionDto = $this->createTestTransactionDTO();
        User::factory(['id' => 1])->create();
        $response = $this->postJson(
            '/transaction',
            $testTransactionDto->toJson(),
        );
        $response
            ->assertStatus(ResponseAlias::HTTP_BAD_REQUEST)
            ->assertJson([
                'error' => trans('transaction::exceptions.' . TransactionException::USER_NOT_FOUND),
            ]);
    }

    /** @test */
    public function should_not_find_role_and_return_400()
    {
        $testTransactionDto = $this->createTestTransactionDTO();
        User::factory(['id' => 1])->create();
        User::factory(['id' => 2])->create();
        Role::factory(['id' => 3])->create();
        $response = $this->postJson(
            '/transaction',
            $testTransactionDto->toJson(),
        );
        $response
            ->assertStatus(ResponseAlias::HTTP_BAD_REQUEST)
            ->assertJson([
                'error' => trans('transaction::exceptions.' . TransactionException::ROLE_NOT_FOUND),
            ]);
    }

    /** @test */
    public function should_return_422_payer_is_not_customer()
    {
        $testTransactionDto = $this->createTestTransactionDTO();
        User::factory(['id' => 1, 'role_id' => 1])->create();
        User::factory(['id' => 2])->create();
        Role::factory(['id' => 1, 'name' => 'SELLER'])->create();
        $response = $this->postJson(
            '/transaction',
            $testTransactionDto->toJson(),
        );
        $response
            ->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'error' => trans('transaction::exceptions.' . TransactionException::USER_NOT_CUSTOMER),
            ]);
    }

    /** @test */
    public function should_return_422_not_enough_credits()
    {
        $testTransactionDto = $this->createTestTransactionDTO();
        User::factory(['id' => 1, 'role_id' => 2, 'balance' => 5.0])->create();
        User::factory(['id' => 2])->create();
        Role::factory(['id' => 2, 'name' => 'CUSTOMER'])->create();
        $response = $this->postJson(
            '/transaction',
            $testTransactionDto->toJson(),
        );
        $response
            ->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'error' => trans('transaction::exceptions.' . TransactionException::NOT_ENOUGH_CREDITS),
            ]);
    }
}

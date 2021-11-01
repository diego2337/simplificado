<?php

namespace Modules\Transaction\Tests\Unit\Services;

use App\Exceptions\BuildException;
use App\Models\Role;
use App\Models\User;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
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
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionServiceTest extends TestCase
{
    use RefreshDatabase, CreateTestTransactionDTO, CreateTransactionSuccess;

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
    public function should_return_transaction_exception_for_payer_not_found()
    {
        $this->expectException(BuildException::class);
        $this->expectExceptionMessage(trans('transaction::exceptions.' . TransactionException::USER_NOT_FOUND));
        $testTransactionDTO = $this->createTestTransactionDTO();
        $this->transactionService->transaction($testTransactionDTO);
    }

    /** @test */
    public function should_return_transaction_exception_for_payee_not_found()
    {
        $this->expectException(BuildException::class);
        $this->expectExceptionMessage(trans('transaction::exceptions.' . TransactionException::USER_NOT_FOUND));
        User::factory(['id' => 1])->create();
        $testTransactionDto = $this->createTestTransactionDTO();
        $this->transactionService->transaction($testTransactionDto);
    }

    /** @test */
    public function should_return_role_not_found()
    {
        $this->expectException(BuildException::class);
        $this->expectExceptionMessage(trans('transaction::exceptions.' . TransactionException::ROLE_NOT_FOUND));
        User::factory(['id' => 1])->create();
        User::factory(['id' => 2])->create();
        Role::factory(['id' => 3])->create();
        $testTransactionDto = $this->createTestTransactionDTO();
        $this->transactionService->transaction($testTransactionDto);
    }

    /** @test */
    public function should_return_payer_not_customer()
    {
        $this->expectException(BuildException::class);
        $this->expectExceptionMessage(trans('transaction::exceptions.' . TransactionException::USER_NOT_CUSTOMER));
        User::factory(['id' => 1, 'role_id' => 1])->create();
        User::factory(['id' => 2])->create();
        Role::factory(['id' => 1, 'name' => 'SELLER'])->create();
        $testTransactionDto = $this->createTestTransactionDTO();
        $this->transactionService->transaction($testTransactionDto);
    }

    /** @test */
    public function should_return_has_not_enough_credits()
    {
        $this->expectException(BuildException::class);
        $this->expectExceptionMessage(trans('transaction::exceptions.' . TransactionException::NOT_ENOUGH_CREDITS));
        User::factory(['id' => 1, 'role_id' => 2, 'balance' => 5.0])->create();
        User::factory(['id' => 2])->create();
        Role::factory(['id' => 2, 'name' => 'CUSTOMER'])->create();
        $testTransactionDto = $this->createTestTransactionDTO();
        $this->transactionService->transaction($testTransactionDto);
    }

    /** @test */
    public function should_return_authorizer_request_exception()
    {
        $this->expectException(GuzzleException::class);
        $authorizerMock = new MockHandler([
            new RequestException(
                'Error',
                new Request('GET', config('client.local.authorizerUrl'))
            ),
        ]);
        $handlerStack = HandlerStack::create($authorizerMock);
        $this->transactionService->setAuthorizerClient(
            new Client(['handler' => $handlerStack]),
        );
        User::factory(['id' => 1, 'role_id' => 2, 'balance' => 50.0])->create();
        User::factory(['id' => 2])->create();
        Role::factory(['id' => 2, 'name' => 'CUSTOMER'])->create();
        $testTransactionDto = $this->createTestTransactionDTO();
        $this->transactionService->transaction($testTransactionDto);
    }

    /** @test */
    public function should_return_transaction_successful()
    {
        $this->createTransactionSuccess($this->transactionService);
        $testTransactionDto = $this->createTestTransactionDTO();
        $resultMessage = $this->transactionService->transaction($testTransactionDto);
        $this->assertEquals('Transaction successful', $resultMessage);
    }
}

<?php

namespace Modules\Transaction\Tests\Unit\Console;

use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use JetBrains\PhpStorm\Pure;
use Modules\Transaction\Console\EmailResendCommand;
use Modules\Transaction\DTO\TransactionDTO;
use Modules\Transaction\Http\Clients\REST\NotifierClient;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmailResendCommandTest extends TestCase
{
    use RefreshDatabase;

    private EmailResendCommand $emailResendCommand;
    public function setUp(): void
    {
        parent::setUp();
        $payee = $this->createPayee();
        $transactionDTO = $this->createTestTransactionDTO(
            15,
            1,
            $payee->getAttribute('id'),
        );
        $this->emailResendCommand = new EmailResendCommand(
            $payee,
            $transactionDTO,
        );
    }

    public function createPayee(): User
    {
        return User::factory()->create()->first();
    }

    #[Pure]
    public function createTestTransactionDTO(int $value, int $payer, int $payee): TransactionDTO
    {
        return new TransactionDTO(
            value: $value,
            payer: $payer,
            payee: $payee,
        );
    }

    /** @test */
    public function should_return_notifier_request_exception()
    {
        $this->expectException(GuzzleException::class);
        $notifierMock = new MockHandler([
            new RequestException(
                'Error',
                new Request('GET', config('client.local.notifierUrl'))
            ),
        ]);
        $notifierHandlerStack = HandlerStack::create($notifierMock);
        $this->emailResendCommand->setNotifierClient(
            new Client(['handler' => $notifierHandlerStack]),
        );
        $this->emailResendCommand->handle();
    }

    /** @test */
    public function should_return_command_successful()
    {
        $this->emailResendCommand->handle();
        $this->artisan('email:resend')
            ->assertExitCode(0);
    }
}

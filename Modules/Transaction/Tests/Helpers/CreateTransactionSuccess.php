<?php

declare(strict_types=1);

namespace Modules\Transaction\Tests\Helpers;

use App\Models\Role;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Modules\Transaction\Http\Clients\REST\AuthorizerClient;
use Modules\Transaction\Http\Clients\REST\NotifierClient;
use Modules\Transaction\Services\TransactionService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

trait CreateTransactionSuccess
{
    public function createTransactionSuccess(TransactionService $transactionService)
    {
        $authorizerMock = new MockHandler([
            new Response(
                ResponseAlias::HTTP_OK,
                [],
                json_encode([
                    'message' => AuthorizerClient::STATUS,
                ])
            ),
        ]);
        $authorizerHandlerStack = HandlerStack::create($authorizerMock);
        $transactionService->setAuthorizerClient(
            new Client(['handler' => $authorizerHandlerStack]),
        );
        $notifierMock = new MockHandler([
            new Response(
                ResponseAlias::HTTP_OK,
                [],
                json_encode([
                    'message' => NotifierClient::STATUS,
                ])
            ),
        ]);
        $notifierHandlerStack = HandlerStack::create($notifierMock);
        $transactionService->setNotifierClient(
            new Client(['handler' => $notifierHandlerStack]),
        );
        User::factory(['id' => 1, 'role_id' => 2, 'balance' => 50.0])->create();
        User::factory(['id' => 2])->create();
        Role::factory(['id' => 2, 'name' => 'CUSTOMER'])->create();
    }
}
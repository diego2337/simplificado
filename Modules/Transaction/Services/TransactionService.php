<?php

declare(strict_types=1);

namespace Modules\Transaction\Services;

use App\Models\Role;
use App\Models\User;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Transaction\Console\EmailResendCommand;
use Modules\Transaction\Exceptions\TransactionException;
use Modules\Transaction\DTO\TransactionDTO;
use Modules\Transaction\Http\Clients\REST\AuthorizerClient;
use Modules\Transaction\Http\Clients\REST\NotifierClient;


class TransactionService
{
    protected UserRepository $userRepository;
    protected RoleRepository $roleRepository;
    protected AuthorizerClient $authorizerClient;
    protected NotifierClient $notifierClient;

    public function __construct(
        UserRepository $userRepository,
        RoleRepository $roleRepository,
        AuthorizerClient $authorizerClient,
        NotifierClient $notifierClient,
    )
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->authorizerClient = $authorizerClient;
        $this->notifierClient = $notifierClient;
    }

    public function getAuthorizerClient(): AuthorizerClient
    {
        return $this->authorizerClient;
    }

    public function setAuthorizerClient(Client $authorizerClient): void
    {
        $this->authorizerClient->setClient($authorizerClient);
    }

    public function getNotifierClient(): NotifierClient
    {
        return $this->notifierClient;
    }

    public function setNotifierClient(Client $notifierClient): void
    {
        $this->notifierClient->setClient($notifierClient);
    }

    public function transaction(TransactionDTO $transactionDTO): string
    {
        $payer = $this->userRepository->find($transactionDTO->payer);
        $payee = $this->userRepository->find($transactionDTO->payee);
        $this->validateUser($payer);
        $this->validateUser($payee);

        $role = $payer?->role()->get()->first();
        $this->validateRole($role);

        Log::info("TransactionService::transaction validated payer, payee and role existence without errors");

        if ($this->isSeller($role)) {
            Log::error("TransactionService::transaction error");
            TransactionException::userNotCustomer();
        }
        if (!$this->hasEnoughCredit($payer, $transactionDTO->value))
        {
            Log::error("TransactionService::transaction error");
            TransactionException::notEnoughCredit();
        }
        if ($this->isAuthorized())
        {
            $this->makeTransaction($payer, $payee, $transactionDTO);
            $this->notifyAndDispatch($payee, $transactionDTO);
            Log::info("TransactionService::transaction transaction successful");
            return 'Transaction successful';
        }
        Log::info("TransactionService::transaction the transaction couldn't be completed");
        return 'There was an error processing the transaction';
    }

    public function validateUser(?User $user): void
    {
        if ($user == null) {
            TransactionException::userNotFound();
        }
    }

    public function validateRole(?Role $role): void
    {
        if ($role == null) {
            TransactionException::roleNotFound();
        }
    }

    public function isSeller(Role $role): bool
    {
        return $role?->getAttribute('name') == Role::SELLER;
    }

    public function hasEnoughCredit(User $user, float $balance): bool
    {
        return $user->getAttribute('balance') >= $balance;
    }

    public function isAuthorized(): bool
    {
        $response = $this->authorizerClient->request();
        return $response->status == AuthorizerClient::STATUS;
    }

    public function makeTransaction(User $payer, User $payee, TransactionDTO $transactionDTO): void
    {
        Log::info("TransactionService::makeTransaction begin database transaction");
        DB::transaction(function () use ($payer, $payee, $transactionDTO) {
            $payerBalance = $payer->getAttribute('balance') - $transactionDTO->value;
            $this->userRepository->update($payer, [
                'balance' => $payerBalance,
            ]);

            $payeeBalance = $payee->getAttribute('balance') + $transactionDTO->value;
            $this->userRepository->update($payee, [
                'balance' => $payeeBalance,
            ]);
        });
        Log::info("TransactionService::makeTransaction end database transaction");
    }

    public function notifyAndDispatch(User $payee, TransactionDTO $transactionDTO)
    {
        $emailResendCommand = new EmailResendCommand($payee, $transactionDTO);
        $emailResendCommand->handle();
    }
}

<?php

declare(strict_types=1);

namespace Modules\Transaction\Services;

use App\Models\Role;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;
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
    protected AuthorizerClient $authorizerClient;
    protected NotifierClient $notifierClient;

    public function __construct(
        UserRepository $userRepository,
        AuthorizerClient $authorizerClient,
        NotifierClient $notifierClient,
    )
    {
        $this->userRepository = $userRepository;
        $this->authorizerClient = $authorizerClient;
        $this->notifierClient = $notifierClient;
    }

    public function transaction(TransactionDTO $transactionDTO): string
    {
        $payer = $this->userRepository->find($transactionDTO->payer);
        $payee = $this->userRepository->find($transactionDTO->payee);
        $this->validateUser($payer);
        $this->validateUser($payee);

        $role = $payer?->role()->get();
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

    public function validateRole(?Collection $role): void
    {
        if ($role == null) {
            TransactionException::roleNotFound();
        }
    }

    public function isSeller(Collection $role): bool
    {
        return $role?->get('name') == Role::SELLER;
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
            $payer->update([
                'balance' => $payerBalance,
            ]);

            $payeeBalance = $payee->getAttribute('balance') + $transactionDTO->value;
            $payee->update([
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

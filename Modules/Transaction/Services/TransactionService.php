<?php

declare(strict_types=1);

namespace Modules\Transaction\Services;

use App\Models\Role;
use App\Repositories\BaseRepository;
use App\Repositories\UserRepository;
use Modules\Transaction\Exceptions\TransactionException;
use Modules\Transaction\DTO\TransactionDTO;


class TransactionService extends BaseRepository
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function transaction(TransactionDTO $transactionDTO): string
    {
        $user = $this->userRepository->find($transactionDTO->payer);
        $role = $user->role()->get();
        if ($role->get('name') == Role::SELLER)
        {
            TransactionException::userNotCustomer();
        }
        return 'Transaction successful';
    }
}

<?php

declare(strict_types=1);

namespace Modules\Transaction\Tests\Helpers;

use Modules\Transaction\DTO\TransactionDTO;

trait CreateTestTransactionDTO
{
    public function createTestTransactionDTO(): TransactionDTO
    {
        return new TransactionDTO(
            value: 15.0,
            payer: 1,
            payee: 2,
        );
    }
}

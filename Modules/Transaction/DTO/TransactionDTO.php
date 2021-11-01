<?php

namespace Modules\Transaction\DTO;

class TransactionDTO
{
    public function __construct(
        public float $value = 0.0,
        public int $payer = 0,
        public int $payee = 0,
    )
    {}

    public function toJson(): array
    {
        return [
            'value' => $this->value,
            'payer' => $this->payer,
            'payee' => $this->payee,
        ];
    }
}
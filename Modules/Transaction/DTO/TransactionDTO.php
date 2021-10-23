<?php

namespace Modules\Transaction\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class TransactionDTO extends DataTransferObject
{
    /** @var float */
    public float $value;

    /** @var int  */
    public int $payer;

    /** @var int */
    public int $payee;

}
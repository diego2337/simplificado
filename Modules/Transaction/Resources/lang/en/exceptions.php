<?php

use Modules\Transaction\Exceptions\TransactionException;

return [
    TransactionException::USER_NOT_FOUND => 'User not found.',
    TransactionException::ROLE_NOT_FOUND => 'Role not found.',
    TransactionException::USER_NOT_CUSTOMER => 'Payer is not a customer',
    TransactionException::NOT_ENOUGH_CREDITS => 'Payer does not have enough credit',
];
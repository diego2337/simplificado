<?php

declare(strict_types=1);

namespace Modules\Transaction\Exceptions;

use App\Exceptions\BuildException;
use Exception;
use Illuminate\Http\Response;

class TransactionException extends BuildException
{
    public static function userNotCustomer()
    {
        $userNotCustomer = new Exception(
            'Payer is not customer',
            Response::HTTP_BAD_REQUEST,
        );
        throw new BuildException($userNotCustomer);
    }
}
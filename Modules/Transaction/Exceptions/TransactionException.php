<?php

declare(strict_types=1);

namespace Modules\Transaction\Exceptions;

use App\Exceptions\BuildException;
use Exception;
use Illuminate\Http\Response;

class TransactionException extends BuildException
{
    public static function userNotFound()
    {
        $userNotFound = new Exception(
          'User not found',
          Response::HTTP_BAD_REQUEST,
        );
        throw new BuildException($userNotFound);
    }

    public static function roleNotFound()
    {
        $roleNotFound = new Exception(
            'Role not found',
            Response::HTTP_BAD_REQUEST,
        );
        throw new BuildException($roleNotFound);
    }

    public static function userNotCustomer()
    {
        $userNotCustomer = new Exception(
            'Payer is not customer',
            Response::HTTP_UNPROCESSABLE_ENTITY,
        );
        throw new BuildException($userNotCustomer);
    }

    public static function notEnoughCredit()
    {
        $notEnoughCredit = new Exception(
            'Payer does not have enough credit',
            Response::HTTP_UNPROCESSABLE_ENTITY,
        );
        throw new BuildException($notEnoughCredit);
    }
}
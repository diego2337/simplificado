<?php

declare(strict_types=1);

namespace Modules\Transaction\Exceptions;

use App\Exceptions\BuildException;
use Exception;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class TransactionException extends BuildException
{
    public const USER_NOT_FOUND = 'userNotFound';
    public const ROLE_NOT_FOUND = 'roleNotFound';
    public const USER_NOT_CUSTOMER = 'userNotCustomer';
    public const NOT_ENOUGH_CREDITS = 'notEnoughCredits';

    public static function userNotFound()
    {
        $userNotFound = new Exception(
          trans('transaction::exceptions.' . self::USER_NOT_FOUND),
          ResponseAlias::HTTP_BAD_REQUEST,
        );
        throw new BuildException($userNotFound);
    }

    public static function roleNotFound()
    {
        $roleNotFound = new Exception(
            trans('transaction::exceptions.' . self::ROLE_NOT_FOUND),
            ResponseAlias::HTTP_BAD_REQUEST,
        );
        throw new BuildException($roleNotFound);
    }

    public static function userNotCustomer()
    {
        $userNotCustomer = new Exception(
            trans('transaction::exceptions.' . self::USER_NOT_CUSTOMER),
            ResponseAlias::HTTP_UNPROCESSABLE_ENTITY,
        );
        throw new BuildException($userNotCustomer);
    }

    public static function notEnoughCredit()
    {
        $notEnoughCredit = new Exception(
            trans('transaction::exceptions.' . self::NOT_ENOUGH_CREDITS),
            ResponseAlias::HTTP_UNPROCESSABLE_ENTITY,
        );
        throw new BuildException($notEnoughCredit);
    }
}
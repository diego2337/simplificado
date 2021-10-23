<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class BuildException extends Exception
{
    protected Exception $exception;
    protected $message;
    protected $code;

    public function __construct(Exception $exception)
    {
        $this->exception = $exception;
        $this->message   = $exception->getMessage();
        $this->code      = $exception->getCode();
        parent::__construct();
    }

    public function render()
    {
        return response()->json($this->toArray(), $this->code ?? 500);
    }

    public function toArray()
    {
        return [
            'code' => $this->getCode(),
            'message' => $this->getMessage(),
        ];
    }
}

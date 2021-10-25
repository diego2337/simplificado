<?php

declare(strict_types=1);

namespace Modules\Transaction\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class AuthorizerClientResponseDTO extends DataTransferObject
{
    /** @var string */
    public string $status;
}
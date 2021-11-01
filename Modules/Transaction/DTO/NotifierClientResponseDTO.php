<?php

declare(strict_types=1);

namespace Modules\Transaction\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class NotifierClientResponseDTO extends DataTransferObject
{
    /** @var string */
    public string $status;
}
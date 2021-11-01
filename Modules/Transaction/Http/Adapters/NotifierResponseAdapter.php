<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Adapters;

use App\Http\Adapters\RESTResponseAdapterInterface;
use Modules\Transaction\DTO\NotifierClientResponseDTO;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class NotifierResponseAdapter implements RESTResponseAdapterInterface
{
    public static function adapt(string $stringifiedData): NotifierClientResponseDTO
    {
        try {
            $json = json_decode($stringifiedData);
            return new NotifierClientResponseDTO([
                'status' => $json->message,
            ]);
        } catch (UnknownProperties $unknownProperties) {
            throw $unknownProperties;
        }
    }
}
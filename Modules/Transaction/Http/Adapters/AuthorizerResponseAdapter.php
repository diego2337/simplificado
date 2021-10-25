<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Adapters;

use App\Http\Adapters\RESTResponseAdapterInterface;
use Modules\Transaction\DTO\AuthorizerClientResponseDTO;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class AuthorizerResponseAdapter implements RESTResponseAdapterInterface
{
    public static function adapt(string $stringifiedData): AuthorizerClientResponseDTO
    {
        try {
            $json = json_decode($stringifiedData);
            return new AuthorizerClientResponseDTO(
                status: $json?->message,
            );
        } catch (UnknownProperties $unknownProperties) {
            throw $unknownProperties;
        }
    }
}
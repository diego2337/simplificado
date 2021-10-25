<?php

declare(strict_types=1);

namespace App\Http\Adapters;

interface RESTResponseAdapterInterface
{
    public static function adapt(string $stringifiedData);
}
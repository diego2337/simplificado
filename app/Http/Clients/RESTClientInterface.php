<?php

declare(strict_types=1);

namespace App\Http\Clients;

interface RESTClientInterface
{
    public function request(string $method = '', string $uri = '', array $options = []);
}
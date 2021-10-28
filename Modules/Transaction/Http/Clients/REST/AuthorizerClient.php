<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Clients\REST;

use App\Http\Clients\RESTClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Modules\Transaction\DTO\AuthorizerClientResponseDTO;
use Modules\Transaction\Http\Adapters\AuthorizerResponseAdapter;

class AuthorizerClient implements RESTClientInterface
{
    public const STATUS = 'Autorizado';
    private string $environment;
    private Client $client;
    public function __construct()
    {
        $this->environment = App::environment();
        $this->client = new Client([
           'base_uri' => config('client.' . $this->environment . '.authorizerUrl'),
        ]);
    }

    public function request(string $method = 'GET', string $uri = '', array $options = []): AuthorizerClientResponseDTO
    {
        try {
            Log::info("AuthorizerClient::request make request \n", [
                "method" => $method,
                "requestUri" => config('client.' . $this->environment . '.authorizerUrl'),
                "options" => $options,
            ]);
            $response = $this->client->request($method, $uri, $options);
            return AuthorizerResponseAdapter::adapt($response->getBody()->getContents());
        } catch (GuzzleException $guzzleException) {
            Log::error("AuthorizerClient::request error \n", [
                "message" => $guzzleException->getMessage(),
                "trace" => $guzzleException->getTraceAsString(),
            ]);
            throw $guzzleException;
        }
    }
}
<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Clients\REST;

use App\Http\Clients\RESTClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Modules\Transaction\DTO\NotifierClientResponseDTO;
use Modules\Transaction\Http\Adapters\NotifierResponseAdapter;

class NotifierClient implements RESTClientInterface
{
    public const STATUS = 'Autorizado';
    private string $environment;
    private Client $client;
    public function __construct()
    {
        $this->environment = App::environment();
        $this->client = new Client([
            'base_uri' => config('client.' . $this->environment . '.notifierUrl'),
        ]);
    }

    public function request(string $method = 'GET', string $uri = '', array $options = []): NotifierClientResponseDTO
    {
        try {
            Log::info("NotifierClient::request make request \n", [
                "method" => $method,
                "requestUri" => config('client.' . $this->environment . '.authorizerUrl'),
                "options" => $options,
            ]);
            $response = $this->client->request($method, $uri, $options);
            return NotifierResponseAdapter::adapt($response->getBody()->getContents());
        } catch (GuzzleException $guzzleException) {
            Log::error("NotifierClient::request error \n", [
                "message" => $guzzleException->getMessage(),
                "trace" => $guzzleException->getTraceAsString(),
            ]);
            throw $guzzleException;
        }
    }
}
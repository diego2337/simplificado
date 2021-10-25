<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Clients\REST;

use App\Http\Clients\RESTClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\App;
use Modules\Transaction\DTO\NotifierClientResponseDTO;
use Modules\Transaction\Http\Adapters\NotifierResponseAdapter;

class NotifierClient implements RESTClientInterface
{
    public const STATUS = 'Autorizado';
    private Client $client;
    public function __construct()
    {
        $environment = App::environment();
        $this->client = new Client([
            'base_uri' => config('client.' . $environment . '.notifierUrl'),
        ]);
    }

    public function request(string $method = 'GET', string $uri = '', array $options = []): NotifierClientResponseDTO
    {
        try {
            $response = $this->client->request($method, $uri, $options);
            return NotifierResponseAdapter::adapt($response->getBody()->getContents());
        } catch (GuzzleException $guzzleException) {
            throw $guzzleException;
        }
    }
}
<?php

namespace App\Shared\HttpServer\Lib\Client;

use App\Shared\HttpServer\Lib\Client\Exception\ServerNotfoundException;
use Swoole\Coroutine\Http\Client;

class HttpClient
{
    private Client $client;

    public function __construct($host, $port, private $isJsonBody = false)
    {
        $this->client = new Client($host, $port, false);
        $this->client->set(
            [
                'timeout' => -1,
            ]
        );
        $this->client->setHeaders(
            [
                'Accept-Encoding' => 'gzip',
                'Accept' => 'application/json',
            ]
        );
    }

    public function get(string $url): mixed
    {
        $isSendRequest = $this->client->get($url);
        if (!$isSendRequest)
            throw new ServerNotfoundException();

        if ($this->isJsonBody)
            return json_decode($this->client->body, true);

        return $this->client->body;
    }

    public function post(string $url, mixed $params): mixed
    {
        $isSendRequest = $this->client->post($url, $params);
        if (!$isSendRequest)
            throw new ServerNotfoundException();

        if ($this->isJsonBody)
            return json_decode($this->client->body, true);

        return $this->client->body;
    }
}
<?php

namespace App\Shared\HttpServer\Lib\Client;

use Swoole\Coroutine\Http\Client;

class HttpClient
{
    private Client $client;

    public function __construct($host, $port)
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
                'Accept' => 'gzip',
            ]
        );
    }

    public function get(string $url): mixed
    {
        $this->client->get($url);
        return $this->client->body;
    }

    public function post(string $url, mixed $params): mixed
    {
        $this->client->post($url, $params);
        return $this->client->body;
    }
}
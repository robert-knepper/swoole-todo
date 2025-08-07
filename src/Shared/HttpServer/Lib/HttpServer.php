<?php
namespace App\Shared\HttpServer\Lib;

use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;
use function Swoole\Coroutine\go;

class HttpServer
{
    private Server $server;

    public function __construct($host, $port)
    {
        $this->server = new Server($host, $port);
        $this->server->on('start', [$this, 'onStart']);
        $this->server->on('request', [$this, 'onRequest']);
    }

    public function onStart()
    {
        echo "start server on {$this->server->host}:{$this->server->port}\n";
    }

    public function onRequest(Request $request, Response $response)
    {
        go(function () use ($request, $response): void {
            $this->requestLogger($request);
            APP->getRouter()->dispatch($request, $response);
        });
    }

    public function start(): void
    {
        $this->server->start();
    }

    private function requestLogger(Request $request) : void
    {
        $uri = "{$request->server['request_method']} - {$request->server['request_uri']}";
        dump($uri,$request->post,'','');
    }

}
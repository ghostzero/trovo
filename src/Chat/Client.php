<?php

namespace GhostZero\Trovo\Chat;

use Exception;
use Ratchet\Client\Connector;
use Ratchet\Client\WebSocket;
use Ratchet\RFC6455\Messaging\MessageInterface;
use React\EventLoop\Factory;

class Client
{
    private ClientOptions $options;

    public function __construct(ClientOptions $options)
    {
        $this->options = $options;
    }

    public function connect()
    {
        $loop = Factory::create();
        $reactConnector = new \React\Socket\Connector($loop, [
            'dns' => '1.1.1.1',
            'timeout' => 10
        ]);
        $connector = new Connector($loop, $reactConnector);

        $connector('wss://open-chat.trovo.live/chat', ['protocol1', 'subprotocol2'], ['Origin' => 'https://ghostzero.dev'])
            ->then(function (WebSocket $conn) {
                $conn->on('message', function (MessageInterface $msg) use ($conn) {
                    echo "Received: {$msg}\n";
                    $conn->close();
                });

                $conn->on('close', function ($code = null, $reason = null) {
                    echo "Connection closed ({$code} - {$reason})\n";
                });

                $conn->send('Hello World!');
            }, function (Exception $e) use ($loop) {
                echo "Could not connect: {$e->getMessage()}\n";
                $loop->stop();
            });

        $loop->run();
    }

}
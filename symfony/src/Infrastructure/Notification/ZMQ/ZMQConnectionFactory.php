<?php

namespace App\Infrastructure\Notification\ZMQ;

use ZMQSocket;

/**
 * Class ZMQConnectionFactory.
 */
class ZMQConnectionFactory
{
    /**
     * @var string
     */
    private $zmqConfig;

    /**
     * ZMQConnectionFactory constructor.
     */
    public function __construct(string $zmqConfig)
    {
        $this->zmqConfig = $zmqConfig;
    }

    /**
     * @throws ZMQSocketException
     * @throws \ZMQSocketException
     */
    public function create(): ZMQSocket
    {
        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect($this->zmqConfig);

        return $socket;
    }
}

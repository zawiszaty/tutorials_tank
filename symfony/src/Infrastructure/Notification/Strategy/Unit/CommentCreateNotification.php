<?php

namespace App\Infrastructure\Notification\Strategy\Unit;

use App\Infrastructure\Notification\Strategy\NotificationStrategyInterface;
use App\Infrastructure\Notification\ZMQ\ZMQConnectionFactory;

/**
 * Class CommentCreateNotification.
 */
class CommentCreateNotification implements NotificationStrategyInterface
{
    /**
     * @var ZMQConnectionFactory
     */
    private $ZMQConnectionFactory;

    public function __construct(ZMQConnectionFactory $ZMQConnectionFactory)
    {
        $this->ZMQConnectionFactory = $ZMQConnectionFactory;
    }

    /**
     * @throws \ZMQSocketException
     */
    public function notify(array $data): void
    {
        $socket = $this->ZMQConnectionFactory->create();
        $socket->send(json_encode(['user' => $data['user'], 'content' => $data['content'], 'type' => $data['type']]));
    }
}

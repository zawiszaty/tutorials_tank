<?php

namespace App\Infrastructure\Notification\Strategy\Unit;

use App\Infrastructure\Notification\Strategy\NotificationStrategyInterface;

/**
 * Class CommentCreateNotification.
 */
class CommentCreateNotification implements NotificationStrategyInterface
{
    /**
     * @param array $data
     *
     * @throws \ZMQSocketException
     */
    public static function notify(array $data)
    {
        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect('tcp://localhost:5555');
        $socket->send(json_encode(['user' => $data['user'], 'content' => $data['content'], 'type' => $data['type']]));
    }
}

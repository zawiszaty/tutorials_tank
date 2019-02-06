<?php

declare(strict_types=1);

namespace App\Infrastructure\Share\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class DemoEventsConsumer
 *
 * @package App\Infrastructure\Share\Consumer
 */
class DemoEventsConsumer implements ConsumerInterface
{
    /**
     * @param AMQPMessage $msg
     */
    public function execute(AMQPMessage $msg): void
    {
        var_dump(unserialize($msg->body));
        die();
    }
}

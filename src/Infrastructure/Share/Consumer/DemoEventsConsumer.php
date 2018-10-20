<?php

declare(strict_types=1);

namespace App\Infrastructure\Share\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class DemoEventsConsumer implements ConsumerInterface
{
    public function execute(AMQPMessage $msg): void
    {
        var_dump(unserialize($msg->body));
        die();
    }
}
<?php

declare(strict_types=1);

namespace App\Consumer;

use PhpAmqpLib\Message\AMQPMessage;

class DemoEventsConsumer
{
    public function execute(AMQPMessage $msg): void
    {
        var_dump(unserialize($msg->body));
    }
}

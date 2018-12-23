<?php

namespace App\Infrastructure\Share\Event\Consumer;

use Broadway\Domain\DomainEventStream;
use Broadway\EventHandling\EventBus;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class PublishProjectionsConsumer
 * @package App\Infrastructure\Share\Event\Consumer
 */
class PublishProjectionsConsumer implements ConsumerInterface
{
    /**
     * @var EventBus
     */
    private $eventBus;

    /**
     * PublishProjectionsConsumer constructor.
     * @param EventBus $eventBus
     */
    public function __construct(EventBus $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    /**
     * @param AMQPMessage $msg
     * @return mixed|void
     */
    public function execute(AMQPMessage $msg): void
    {
        /** @var DomainEventStream $domainEventStream */
        $domainEventStream = unserialize($msg->body);
        $this->eventBus->publish($domainEventStream);
    }
}
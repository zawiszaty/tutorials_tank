<?php

namespace App\Infrastructure\Share\Event\Consumer;

use App\Infrastructure\Share\Broadway\Projector\Projector;
use Broadway\Domain\DomainMessage;
use Broadway\EventHandling\EventBus;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class PublishProjectionsConsumer.
 */
class PublishProjectionsConsumer implements ConsumerInterface
{
    /**
     * @var EventBus
     */
    private $eventBus;

    /**
     * PublishProjectionsConsumer constructor.
     *
     * @param EventBus $eventBus
     */
    public function __construct(EventBus $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    /**
     * @param AMQPMessage $msg
     *
     * @return mixed|void
     */
    public function execute(AMQPMessage $msg): void
    {
        $message = explode(' ', $msg->body);
        /** @var DomainMessage $domainMessage */
        $domainMessage = unserialize($message[0]);
        /** @var Projector $projector */
        $projector = unserialize($message[1]);
        $projector->handleAsyncProjection($domainMessage);
    }
}

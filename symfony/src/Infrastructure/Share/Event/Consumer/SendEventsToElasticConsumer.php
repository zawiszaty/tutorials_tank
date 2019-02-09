<?php

namespace App\Infrastructure\Share\Event\Consumer;

use App\Infrastructure\Share\Event\Query\EventElasticRepository;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class SendEventsToElasticConsumer.
 */
class SendEventsToElasticConsumer implements ConsumerInterface
{
    /**
     * @param AMQPMessage $msg
     */
    public function execute(AMQPMessage $msg): void
    {
        $this->eventElasticRepository->storeEvent(unserialize($msg->body));
    }

    /**
     * SendEventsToElasticConsumer constructor.
     *
     * @param EventElasticRepository $eventElasticRepository
     */
    public function __construct(EventElasticRepository $eventElasticRepository)
    {
        $this->eventElasticRepository = $eventElasticRepository;
    }

    private $eventElasticRepository;
}

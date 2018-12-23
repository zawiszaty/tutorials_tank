<?php

namespace App\Infrastructure\Share\Event\Consumer;

use App\Infrastructure\Share\Event\Query\EventElasticRepository;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class SendEventsToElasticConsumer
 * @package App\Infrastructure\Share\Event\Consumer
 */
class SendEventsToElasticConsumer implements ConsumerInterface
{
    public function execute(AMQPMessage $msg): void
    {
        $this->eventElasticRepository->storeEvent(unserialize($msg->body));
    }

    public function __construct(EventElasticRepository $eventElasticRepository)
    {
        $this->eventElasticRepository = $eventElasticRepository;
    }

    private $eventElasticRepository;
}

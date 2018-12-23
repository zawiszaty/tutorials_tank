<?php

declare(strict_types=1);

namespace App\Infrastructure\Share\Event\Producer;

use Broadway\Domain\DomainEventStream;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

class EventToProjectionsProducer
{
    private $producer;

    public function __construct(ProducerInterface $eventProducer)
    {
        $this->producer = $eventProducer;
    }

    public function add(DomainEventStream $domainMessage)
    {
        $this->producer->publish(serialize($domainMessage), 'projections');
    }
}

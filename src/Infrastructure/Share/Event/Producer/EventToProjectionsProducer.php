<?php

declare(strict_types=1);

namespace App\Infrastructure\Share\Event\Producer;

use App\Infrastructure\Share\Broadway\Projector\Projector;
use Broadway\Domain\DomainMessage;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

class EventToProjectionsProducer
{
    private $producer;

    public function __construct(ProducerInterface $eventProducer)
    {
        $this->producer = $eventProducer;
    }

    public function add(DomainMessage $domainMessage, Projector $projector)
    {
        $message = [
            serialize($domainMessage),
            serialize($projector),
        ];
        $this->producer->publish(implode(" ", $message), 'projections');
    }
}

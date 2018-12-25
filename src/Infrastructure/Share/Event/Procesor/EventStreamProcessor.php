<?php

namespace App\Infrastructure\Share\Event\Procesor;

use App\Infrastructure\Share\Event\EventStreamProcessorInterface;
use Broadway\Domain\DomainEventStream;
use Broadway\EventHandling\EventBus;

class EventStreamProcessor implements EventStreamProcessorInterface
{
    public function process(DomainEventStream $stream)
    {
        $events = [];
        foreach ($stream->getIterator() as $event) {
            $events[] = $event;
        }
        $this->eventBus->publish(new DomainEventStream($events));
    }

    public function __construct(EventBus $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    /**
     * @var EventBus
     */
    private $eventBus;
}

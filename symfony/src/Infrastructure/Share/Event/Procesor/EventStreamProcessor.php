<?php

namespace App\Infrastructure\Share\Event\Procesor;

use App\Infrastructure\Share\Event\EventStreamProcessorInterface;
use Broadway\Domain\DomainEventStream;
use Broadway\EventHandling\EventBus;

/**
 * Class EventStreamProcessor.
 */
class EventStreamProcessor implements EventStreamProcessorInterface
{
    /**
     * @return mixed|void
     */
    public function process(DomainEventStream $stream)
    {
        $events = [];
        foreach ($stream->getIterator() as $event) {
            $events[] = $event;
        }
        $this->eventBus->publish(new DomainEventStream($events));
    }

    /**
     * EventStreamProcessor constructor.
     */
    public function __construct(EventBus $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    /**
     * @var EventBus
     */
    private $eventBus;
}

<?php

namespace App\Infrastructure\Share\Event\Procesor;

use App\Infrastructure\Share\Event\EventStreamProcessorInterface;
use Broadway\Domain\DomainEventStream;
use Broadway\EventHandling\EventBus;

/**
 * Class EventStreamProcessor
 *
 * @package App\Infrastructure\Share\Event\Procesor
 */
class EventStreamProcessor implements EventStreamProcessorInterface
{
    /**
     * @param DomainEventStream $stream
     *
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
     *
     * @param EventBus $eventBus
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

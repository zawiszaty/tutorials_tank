<?php

declare(strict_types=1);

namespace App\Application\Command\Events\Replay;

use App\Application\Command\CommandHandlerInterface;
use App\Infrastructure\Share\Event\EventStreamProcessorInterface;
use App\Infrastructure\Share\Event\IterableAggregateEventStoreInterface;

class ReplayEventsHandler implements CommandHandlerInterface
{
    public function __invoke(ReplayEventsCommand $command): void
    {
        foreach ($this->iterableDbalEventStore as $stream) {
            $this->eventStreamProcessor->process($stream);
        }
    }

    public function __construct(
        EventStreamProcessorInterface $eventStreamProcessor,
        IterableAggregateEventStoreInterface $iterableDbalEventStore
    ) {
        $this->eventStreamProcessor = $eventStreamProcessor;
        $this->iterableDbalEventStore = $iterableDbalEventStore;
    }

    /**
     * @var EventStreamProcessorInterface
     */
    private $eventStreamProcessor;

    /**
     * @var IterableAggregateEventStoreInterface
     */
    private $iterableDbalEventStore;
}

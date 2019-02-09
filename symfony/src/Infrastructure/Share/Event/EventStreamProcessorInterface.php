<?php

namespace App\Infrastructure\Share\Event;

use Broadway\Domain\DomainEventStream;

/**
 * Interface EventStreamProcessorInterface.
 */
interface EventStreamProcessorInterface
{
    /**
     * @param DomainEventStream $stream
     *
     * @return mixed
     */
    public function process(DomainEventStream $stream);
}

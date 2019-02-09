<?php

namespace App\Infrastructure\Share\Event;

use Broadway\Domain\DomainEventStream;

/**
 * Interface EventStreamProcessorInterface
 *
 * @package App\Infrastructure\Share\Event
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

<?php

namespace App\Infrastructure\Share\Event;

use Broadway\Domain\DomainEventStream;

/**
 * Interface EventStreamProcessorInterface.
 */
interface EventStreamProcessorInterface
{
    public function process(DomainEventStream $stream);
}

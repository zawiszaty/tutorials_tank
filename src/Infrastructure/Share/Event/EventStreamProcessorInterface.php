<?php

namespace App\Infrastructure\Share\Event;

use Broadway\Domain\DomainEventStream;

interface EventStreamProcessorInterface
{
    public function process(DomainEventStream $stream);
}
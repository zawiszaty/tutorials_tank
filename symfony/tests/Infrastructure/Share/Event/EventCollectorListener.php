<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Share\Event;

use Broadway\Domain\DomainMessage;
use Broadway\EventHandling\EventListener;

class EventCollectorListener implements EventListener
{
    public function handle(DomainMessage $domainMessage): void
    {
        $this->publishedEvents[] = $domainMessage;
    }

    public function popEvents(): array
    {
        $events = $this->publishedEvents;
        $this->publishedEvents = [];

        return $events;
    }

    private $publishedEvents = [];
}

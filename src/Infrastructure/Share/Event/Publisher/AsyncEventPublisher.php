<?php

declare(strict_types=1);

namespace App\Infrastructure\Share\Event\Publisher;

use Broadway\Domain\DomainMessage;
use Broadway\EventHandling\EventListener;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class AsyncEventPublisher implements EventPublisher, EventSubscriberInterface, EventListener
{
    public function publish(): void
    {
        if (empty($this->events)) {
            return;
        }
        foreach ($this->events as $event) {
            $this->eventProducer->publish(serialize($event), $event->getType());
        }
    }

    public function handle(DomainMessage $message): void
    {
        $this->events[] = $message;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::TERMINATE => 'publish',
            ConsoleEvents::TERMINATE => 'publish',
        ];
    }

    public function __construct(ProducerInterface $eventProducer)
    {
        $this->eventProducer = $eventProducer;
    }

    /**
     * @var ProducerInterface
     */
    private $eventProducer;
    /** @var DomainMessage[] */
    private $events = [];
}
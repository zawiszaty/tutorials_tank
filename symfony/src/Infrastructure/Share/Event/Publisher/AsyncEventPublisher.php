<?php

declare(strict_types=1);

namespace App\Infrastructure\Share\Event\Publisher;

use Broadway\Domain\DomainMessage;
use Broadway\EventHandling\EventListener;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class AsyncEventPublisher.
 */
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

    /**
     * @param DomainMessage $message
     */
    public function handle(DomainMessage $message): void
    {
        $this->events[] = $message;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::TERMINATE  => 'publish',
            ConsoleEvents::TERMINATE => 'publish',
        ];
    }

    /**
     * AsyncEventPublisher constructor.
     *
     * @param ProducerInterface $eventProducer
     */
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

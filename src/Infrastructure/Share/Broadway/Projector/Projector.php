<?php

declare(strict_types=1);

namespace App\Infrastructure\Share\Broadway\Projector;

use App\Infrastructure\Share\Event\Producer\EventToProjectionsProducer;
use Broadway\Domain\DomainMessage;
use Broadway\EventHandling\EventListener;

/**
 * Handles events and projects to a read model.
 */
abstract class Projector implements EventListener
{
    /**
     * @var EventToProjectionsProducer
     */
    private $eventToProjectionsProducer;

    /**
     * Projector constructor.
     *
     * @param EventToProjectionsProducer $eventToProjectionsProducer
     */
    public function __construct(EventToProjectionsProducer $eventToProjectionsProducer)
    {
        $this->eventToProjectionsProducer = $eventToProjectionsProducer;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(DomainMessage $domainMessage)
    {
        $this->eventToProjectionsProducer->add($domainMessage, $this);
    }

    public function handleAsyncProjection(DomainMessage $domainMessage)
    {
        $event = $domainMessage->getPayload();
        $method = $this->getHandleMethod($event);

        if (!method_exists($this, $method)) {
            return;
        }
        $this->$method($event, $domainMessage);
    }

    private function getHandleMethod($event): string
    {
        $classParts = explode('\\', get_class($event));

        return 'apply'.end($classParts);
    }
}

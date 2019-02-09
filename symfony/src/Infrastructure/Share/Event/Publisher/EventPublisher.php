<?php

declare(strict_types=1);

namespace App\Infrastructure\Share\Event\Publisher;

use Broadway\Domain\DomainMessage;

/**
 * Interface EventPublisher.
 */
interface EventPublisher
{
    /**
     * @param DomainMessage $message
     */
    public function handle(DomainMessage $message): void;

    public function publish(): void;
}

<?php

declare(strict_types=1);

namespace App\Tests\Application\Command\Category;

use App\Application\Command\Category\Create\CreateCategoryCommand;
use App\Domain\Category\Event\CategoryWasCreated;
use App\Tests\Application\ApplicationTestCase;
use App\Tests\Infrastructure\Share\Event\EventCollectorListener;
use Broadway\Domain\DomainMessage;

class CreateCategoryHandlerTest extends ApplicationTestCase
{
    /**
     * @test
     *
     * @group integration
     */
    public function command_handler_must_fire_domain_event(): void
    {
        $name = 'test';
        $command = new CreateCategoryCommand();
        $command->name = $name;
        $this
            ->handle($command);
        /** @var EventCollectorListener $collector */
        $collector = $this->service(EventCollectorListener::class);
        /** @var DomainMessage[] $events */
        $events = $collector->popEvents();
        self::assertCount(1, $events);
        /** @var CategoryWasCreated $userCreatedEvent */
        $userCreatedEvent = $events[0]->getPayload();
        self::assertInstanceOf(CategoryWasCreated::class, $userCreatedEvent);
    }
}

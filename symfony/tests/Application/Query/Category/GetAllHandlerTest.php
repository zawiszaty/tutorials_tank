<?php

declare(strict_types=1);

namespace App\Tests\Application\Query\Category;

use App\Application\Command\Category\Create\CreateCategoryCommand;
use App\Application\Query\Category\GetAll\GetAllCommand;
use App\Domain\Category\Event\CategoryWasCreated;
use App\Tests\Application\ApplicationTestCase;
use App\Tests\Infrastructure\Share\Event\EventCollectorListener;
use Broadway\Domain\DomainMessage;

class GetAllHandlerTest extends ApplicationTestCase
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
        $query = new GetAllCommand(
            1,
            10
        );
        $result = $this->ask($query);
        self::assertNotEmpty($result);
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Application\Command\Category;

use App\Application\Command\Category\ChangeName\ChangeNameCommand;
use App\Application\Command\Category\Create\CreateCategoryCommand;
use App\Domain\Category\Event\CategoryWasCreated;
use App\Domain\Category\Event\NameWasChanged;
use App\Tests\Application\ApplicationTestCase;
use App\Tests\Infrastructure\Share\Event\EventCollectorListener;
use Broadway\Domain\DomainMessage;

class EditCategoryHandlerTest extends ApplicationTestCase
{
    /**
     * @test
     *
     * @group integration
     *
     */
    public function command_handler_must_fire_domain_event(): void
    {
        $name = 'test';
        $command = new CreateCategoryCommand();
        $command->name = $name;
        $this
            ->handle($command);
        $collector = $this->service(EventCollectorListener::class);
        /** @var DomainMessage[] $events */
        $events = $collector->popEvents();
        self::assertCount(1, $events);
        /** @var CategoryWasCreated $categoryWasCreated */
        $categoryWasCreated = $events[0]->getPayload();
        $command = new ChangeNameCommand();
        $command->name = 'test2';
        $command->id = $categoryWasCreated->getId()->toString();
        $this
            ->handle($command);
        /** @var EventCollectorListener $collector */
        $collector = $this->service(EventCollectorListener::class);
        /** @var DomainMessage[] $events */
        $events = $collector->popEvents();
        self::assertCount(1, $events);
        /** @var NameWasChanged $nameWasChanged */
        $nameWasChanged = $events[0]->getPayload();
        self::assertSame('test2', $nameWasChanged->getName()->toString());
        self::assertInstanceOf(NameWasChanged::class, $nameWasChanged);
    }
}
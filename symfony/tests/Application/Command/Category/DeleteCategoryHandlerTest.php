<?php

declare(strict_types=1);

namespace App\Tests\Application\Command\Category;

use App\Application\Command\Category\Create\CreateCategoryCommand;
use App\Application\Command\Category\Delete\DeleteCategoryCommand;
use App\Domain\Category\Event\CategoryWasCreated;
use App\Domain\Category\Event\CategoryWasDeleted;
use App\Tests\Application\ApplicationTestCase;
use App\Tests\Application\Utils\Category\Category;
use App\Tests\Infrastructure\Share\Event\EventCollectorListener;
use Broadway\Domain\DomainMessage;

class DeleteCategoryHandlerTest extends ApplicationTestCase
{
    /**
     * @test
     *
     * @group integration
     *
     * @throws \Assert\AssertionFailedException
     */
    public function command_handler_must_fire_domain_event(): void
    {
        $command = Category::create('test');
        $this->handle($command);
        $collector = $this->service(EventCollectorListener::class);
        /** @var DomainMessage[] $events */
        $events = $collector->popEvents();
        self::assertCount(1, $events);
        /** @var CategoryWasCreated $categoryWasCreated */
        $categoryWasCreated = $events[0]->getPayload();
        $command = new DeleteCategoryCommand($categoryWasCreated->getId()->toString());
        $this
            ->handle($command);
        /** @var EventCollectorListener $collector */
        $collector = $this->service(EventCollectorListener::class);
        /** @var DomainMessage[] $events */
        $events = $collector->popEvents();
        self::assertCount(1, $events);
        /** @var CategoryWasDeleted $categoryWasDeleted */
        $categoryWasDeleted = $events[0]->getPayload();
        self::assertInstanceOf(CategoryWasDeleted::class, $categoryWasDeleted);
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Application\Command\Post\Create;

use App\Domain\Post\Event\CreatePostEvent;
use App\Tests\Application\ApplicationTestCase;
use App\Tests\Application\Utils\Post\Post;
use App\Tests\Infrastructure\Share\Event\EventCollectorListener;
use Broadway\Domain\DomainMessage;

/**
 * Class CreatePostHandlerTest.
 */
class CreatePostHandlerTest extends ApplicationTestCase
{
    /**
     * @test
     *
     * @group integration
     */
    public function command_handler_must_fire_domain_event(): void
    {
        $content = 'test';
        $user = $this->createUser();
        $category = $this->createCategory();
        $command = Post::create($content, $user, $category);
        $this
            ->handle($command);
        /** @var EventCollectorListener $collector */
        $collector = $this->service(EventCollectorListener::class);
        /** @var DomainMessage[] $events */
        $events = $collector->popEvents();
        self::assertCount(1, $events);
        /** @var CreatePostEvent $createPostEvent */
        $createPostEvent = $events[0]->getPayload();
        self::assertInstanceOf(CreatePostEvent::class, $createPostEvent);
    }
}

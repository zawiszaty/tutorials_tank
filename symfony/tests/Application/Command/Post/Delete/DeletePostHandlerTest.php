<?php

declare(strict_types=1);

namespace App\Tests\Application\Command\Post\Delete;

use App\Application\Command\Post\Create\CreatePostCommand;
use App\Application\Command\Post\Delete\DeletePostCommand;
use App\Domain\Post\Event\CreatePostEvent;
use App\Domain\Post\Event\PostEventDelete;
use App\Tests\Application\ApplicationTestCase;
use App\Tests\Application\Utils\Post\Post;
use App\Tests\Infrastructure\Share\Event\EventCollectorListener;
use Broadway\Domain\DomainMessage;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeletePostHandlerTest extends ApplicationTestCase
{
    /**
     * @test
     *
     * @group integration
     * @throws \Exception
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
        $command = new DeletePostCommand($createPostEvent->getId()->toString(), $createPostEvent->getUser());
        $this
            ->handle($command);
        /** @var EventCollectorListener $collector */
        $collector = $this->service(EventCollectorListener::class);
        /** @var DomainMessage[] $events */
        $events = $collector->popEvents();
        self::assertCount(1, $events);
        /** @var PostEventDelete $postEventDelete */
        $postEventDelete = $events[0]->getPayload();
        self::assertInstanceOf(PostEventDelete::class, $postEventDelete);
    }
}

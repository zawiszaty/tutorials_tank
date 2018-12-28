<?php

declare(strict_types=1);

namespace App\Tests\Application\Command\Post\Delete;

use App\Application\Command\Post\Create\CreatePostCommand;
use App\Application\Command\Post\Delete\DeletePostCommand;
use App\Domain\Post\Event\CreatePostEvent;
use App\Domain\Post\Event\PostEventDelete;
use App\Tests\Application\ApplicationTestCase;
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
     *
     */
    public function command_handler_must_fire_domain_event(): void
    {
        $this->expectException(NotFoundHttpException::class);
        copy('public/sample/sample.jpg', 'public/sample/sample2.jpg');
        $file = new File('public/sample/sample2.jpg');
        $uuid = Uuid::uuid4()->toString();
        $content = 'test';
        $command = new CreatePostCommand();
        $command->setContent($content);
        $command->setUser($uuid);
        $command->setType('oder_site');
        $command->setTitle('test');
        $command->setFile($file);
        $command->setThumbnail('test');
        $command->setCategory($uuid);
        $command->setShortDescription('test');
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
        self::assertCount(2, $events);
        /** @var PostEventDelete $postEventDelete */
        $postEventDelete = $events[1]->getPayload();
        self::assertInstanceOf(PostEventDelete::class, $postEventDelete);
    }
}
<?php

declare(strict_types=1);

namespace App\Tests\Application\Command\Comment\Create;

use App\Application\Command\Comment\Create\CreateCommentCommand;
use App\Domain\Comment\Event\CommentWasCreated;
use App\Tests\Application\ApplicationTestCase;
use App\Tests\Infrastructure\Share\Event\EventCollectorListener;
use Broadway\Domain\DomainMessage;
use Ramsey\Uuid\Uuid;

class CreateCommentHandlerTest extends ApplicationTestCase
{
    /**
     * @test
     *
     * @group integration
     *
     * @throws \Exception
     */
    public function command_handler_must_fire_domain_event(): void
    {
        $uuid = Uuid::uuid4()->toString();
        $content = 'test';
        $command = new CreateCommentCommand();
        $command->content = $content;
        $command->user = $this->createUser();
        $command->post = $this->createPost();
        $command->parentComment = null;
        $this
            ->handle($command);
        /** @var EventCollectorListener $collector */
        $collector = $this->service(EventCollectorListener::class);
        /** @var DomainMessage[] $events */
        $events = $collector->popEvents();
        self::assertCount(1, $events);
        /** @var CommentWasCreated $userCreatedEvent */
        $userCreatedEvent = $events[0]->getPayload();
        self::assertInstanceOf(CommentWasCreated::class, $userCreatedEvent);
    }
}

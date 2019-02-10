<?php

declare(strict_types=1);

namespace App\Tests\Application\Command\Comment\Delete;

use App\Application\Command\Comment\Create\CreateCommentCommand;
use App\Application\Command\Comment\Delete\DeleteCommentCommand;
use App\Domain\Comment\Event\CommentWasCreated;
use App\Tests\Application\ApplicationTestCase;
use App\Tests\Infrastructure\Share\Event\EventCollectorListener;
use Broadway\Domain\DomainMessage;

class DeleteCommentHandlerTest extends ApplicationTestCase
{
    /**
     * @test
     *
     * @group integration
     */
    public function command_handler_must_fire_domain_event(): void
    {
        $user = $this->createUser();
        $content = 'test';
        $command = new CreateCommentCommand();
        $command->content = $content;
        $command->user = $user;
        $command->post = $this->createPost();
        $command->parentComment = null;
        $this
            ->handle($command);
        /** @var EventCollectorListener $collector */
        $collector = $this->service(EventCollectorListener::class);
        /** @var DomainMessage[] $events */
        $events = $collector->popEvents();
        self::assertCount(1, $events);
        /** @var CommentWasCreated $commentWasCreated */
        $commentWasCreated = $events[0]->getPayload();
        $command = new DeleteCommentCommand(
            $commentWasCreated->getId()->toString(),
            $user
        );
        $this
            ->handle($command);
    }
}

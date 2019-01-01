<?php

declare(strict_types=1);

namespace App\Tests\Application\Command\Comment\Delete;

use App\Application\Command\Category\Create\CreateCategoryCommand;
use App\Application\Command\Comment\Create\CreateCommentCommand;
use App\Application\Command\Comment\Delete\DeleteCommentCommand;
use App\Application\Command\Post\Create\CreatePostCommand;
use App\Application\Command\User\Create\CreateUserCommand;
use App\Domain\Category\Event\CategoryWasCreated;
use App\Domain\Comment\Event\CommentWasCreated;
use App\Domain\Comment\Event\CommentWasDeletedEvent;
use App\Domain\Post\Event\CreatePostEvent;
use App\Domain\User\Event\UserWasCreated;
use App\Tests\Application\ApplicationTestCase;
use App\Tests\Infrastructure\Share\Event\EventCollectorListener;
use Broadway\Domain\DomainMessage;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\File;

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

    /**
     * @return string
     *
     * @throws \Exception
     */
    private function createUser(): string
    {
        $email = Uuid::uuid4()->toString().'asd@asd.asd';
        $command = new CreateUserCommand();
        $command->setAvatar(Uuid::uuid4()->toString());
        $command->setBanned(false);
        $command->setEmail($email);
        $command->setPlainPassword('test');
        $command->setUsername(Uuid::uuid4()->toString());
        $command->setRoles(['ROLE_USER']);
        $command->setSteemit(Uuid::uuid4()->toString());

        $this
            ->handle($command);
        /** @var EventCollectorListener $collector */
        $collector = $this->service(EventCollectorListener::class);
        /** @var DomainMessage[] $events */
        $events = $collector->popEvents();
        self::assertCount(1, $events);
        /** @var UserWasCreated $userCreatedEvent */
        $userCreatedEvent = $events[0]->getPayload();
        self::assertInstanceOf(UserWasCreated::class, $userCreatedEvent);

        return $userCreatedEvent->getId()->toString();
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    private function createPost(): string
    {
        copy('public/sample/sample.jpg', 'public/sample/sample2.jpg');
        $file = new File('public/sample/sample2.jpg');
        $uuid = Uuid::uuid4()->toString();
        $content = 'test';
        $command = new CreatePostCommand();
        $command->setContent($content);
        $command->setUser($this->createUser());
        $command->setType('oder_site');
        $command->setTitle(Uuid::uuid4()->toString());
        $command->setFile($file);
        $command->setThumbnail(Uuid::uuid4()->toString());
        $command->setCategory($this->createCategory());
        $command->setShortDescription(Uuid::uuid4()->toString());
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

        return $createPostEvent->getId()->toString();
    }

    private function createCategory(): string
    {
        $name = Uuid::uuid4()->toString();
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

        return $userCreatedEvent->getId()->toString();
    }
}

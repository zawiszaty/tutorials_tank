<?php

declare(strict_types=1);

namespace App\Tests\Application\Command\Comment\Create;

use App\Application\Command\Category\Create\CreateCategoryCommand;
use App\Application\Command\Comment\Create\CreateCommentCommand;
use App\Application\Command\Post\Create\CreatePostCommand;
use App\Application\Command\User\Create\CreateUserCommand;
use App\Domain\Category\Event\CategoryWasCreated;
use App\Domain\Comment\Event\CommentWasCreated;
use App\Domain\Post\Event\CreatePostEvent;
use App\Domain\User\Event\UserWasCreated;
use App\Tests\Application\ApplicationTestCase;
use App\Tests\Infrastructure\Share\Event\EventCollectorListener;
use Broadway\Domain\DomainMessage;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
//        $this->expectException(NotFoundHttpException::class);
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
        /** @var CommentWasCreated $commentWasCreated */
        $commentWasCreated = $events[0]->getPayload();
        self::assertSame($content, $commentWasCreated->getContent()->toString());
        self::assertInstanceOf(CommentWasCreated::class, $commentWasCreated);
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    private function createUser(): string
    {
        $email = Uuid::uuid4()->toString() . 'asd@asd.asd';
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

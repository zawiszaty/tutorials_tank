<?php

declare(strict_types=1);

namespace App\Tests\Application\Command\Post\Edit;

use App\Application\Command\Category\Create\CreateCategoryCommand;
use App\Application\Command\Post\Create\CreatePostCommand;
use App\Application\Command\Post\Edit\EditPostCommand;
use App\Application\Command\User\Create\CreateUserCommand;
use App\Domain\Category\Event\CategoryWasCreated;
use App\Domain\Post\Event\CreatePostEvent;
use App\Domain\Post\Event\PostWasEdited;
use App\Domain\User\Event\UserWasCreated;
use App\Tests\Application\ApplicationTestCase;
use App\Tests\Infrastructure\Share\Event\EventCollectorListener;
use Broadway\Domain\DomainMessage;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\File;

class EditPostHandlerTest extends ApplicationTestCase
{
    /**
     * @test
     *
     * @group integration
     */
    public function command_handler_must_fire_domain_event(): void
    {
        copy('public/sample/sample.jpg', 'public/sample/sample2.jpg');
        $file = new File('public/sample/sample2.jpg');
        $content = 'test';
        $command = new CreatePostCommand();
        $command->setContent($content);
        $command->setUser($this->createUser());
        $command->setType('oder_site');
        $command->setTitle('test');
        $command->setFile($file);
        $command->setThumbnail('test');
        $command->setCategory($this->createCategory());
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
        $uuid = Uuid::uuid4()->toString();
        $user = $this->createUser();
        $category = $this->createCategory();
        copy('public/sample/sample.jpg', 'public/sample/sample2.jpg');
        $file = new File('public/sample/sample2.jpg');
        $command = new EditPostCommand();
        $command->setId($createPostEvent->getId()->toString());
        $command->setContent('test2');
        $command->setUser($user);
        $command->setType('oder_site');
        $command->setTitle('test2');
        $command->setFile($file);
        $command->setThumbnail('test2');
        $command->setCategory($category);
        $command->setShortDescription('test2');
        $this
            ->handle($command);
        /** @var EventCollectorListener $collector */
        $collector = $this->service(EventCollectorListener::class);
        /** @var DomainMessage[] $events */
        $events = $collector->popEvents();
        self::assertCount(1, $events);
        /** @var PostWasEdited $editPostEvent */
        $editPostEvent = $events[0]->getPayload();
        self::assertSame('test2', $editPostEvent->getTitle()->toString());
        self::assertSame('test2', $editPostEvent->getShortDescription());
        self::assertSame('test2', $editPostEvent->getContent()->toString());
        self::assertSame($category, $editPostEvent->getCategory());
        self::assertSame($user, $editPostEvent->getUser());
        self::assertInstanceOf(PostWasEdited::class, $editPostEvent);
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

    /**
     * @return string
     */
    private function createUser(): string
    {
        $email = Uuid::uuid4()->toString() . '@asd.asd';
        $command = new CreateUserCommand();
        $command->setAvatar(Uuid::uuid4()->toString());
        $command->setBanned(false);
        $command->setEmail($email);
        $command->setPlainPassword('test');
        $command->setUsername(Uuid::uuid4()->toString());
        $command->setRoles(['ROLE_USER']);
        $command->setSteemit('test');

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
}

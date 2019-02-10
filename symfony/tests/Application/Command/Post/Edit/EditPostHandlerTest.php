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
use App\Tests\Application\Utils\Post\Post;
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
        self::assertSame($category->getId(), $editPostEvent->getCategory());
        self::assertSame($user, $editPostEvent->getUser());
        self::assertInstanceOf(PostWasEdited::class, $editPostEvent);
    }
}

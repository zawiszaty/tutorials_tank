<?php

declare(strict_types=1);

namespace App\Tests\Application\Command\Post\Create;

use App\Application\Command\Category\Create\CreateCategoryCommand;
use App\Application\Command\Post\Create\CreatePostCommand;
use App\Application\Command\User\Create\CreateUserCommand;
use App\Domain\Category\Event\CategoryWasCreated;
use App\Domain\Post\Event\CreatePostEvent;
use App\Domain\User\Event\UserWasCreated;
use App\Tests\Application\ApplicationTestCase;
use App\Tests\Application\Utils\Post\Post;
use App\Tests\Infrastructure\Share\Event\EventCollectorListener;
use Broadway\Domain\DomainMessage;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class CreatePostHandlerTest
 *
 * @package App\Tests\Application\Command\Post\Create
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

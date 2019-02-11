<?php

declare(strict_types=1);

namespace App\Tests\Application;

use App\Domain\Category\Event\CategoryWasCreated;
use App\Domain\Post\Event\CreatePostEvent;
use App\Domain\User\Event\UserWasCreated;
use App\Infrastructure\Category\Query\Projections\CategoryView;
use App\Infrastructure\Post\Query\Projections\PostView;
use App\Tests\Application\Utils\Category\Category;
use App\Tests\Application\Utils\Post\Post;
use App\Tests\Application\Utils\User\User;
use App\Tests\Infrastructure\Share\Event\EventCollectorListener;
use Broadway\Domain\DomainMessage;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use League\Tactician\CommandBus;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class ApplicationTestCase.
 */
abstract class ApplicationTestCase extends KernelTestCase
{
    /** @var CommandBus|null */
    private $commandBus;

    /** @var CommandBus|null */
    private $queryBus;

    /**
     * @param $query
     *
     * @return mixed
     */
    protected function ask($query)
    {
        return $this->queryBus->handle($query);
    }

    /**
     * @param $command
     */
    protected function handle($command): void
    {
        $this->commandBus->handle($command);
    }

    /**
     * @param string $serviceId
     *
     * @return object
     */
    protected function service(string $serviceId)
    {
        return self::$container->get($serviceId);
    }

    protected function setUp(): void
    {
        static::bootKernel();
        $this->service('doctrine.orm.entity_manager')->getConnection()->beginTransaction();
        $this->commandBus = $this->service('tactician.commandbus.command');
        $this->queryBus = $this->service('tactician.commandbus.query');
        /** @var Connection $connection */
        $connection = self::$container->get('doctrine')->getConnection();
        $connection->beginTransaction();
        $connection->query('SET FOREIGN_KEY_CHECKS=0');
        $connection->query('DELETE FROM events');
        $connection->query('DELETE FROM category');
        $connection->query('DELETE FROM fos_user');
        $connection->query('DELETE FROM access_token');
        $connection->query('DELETE FROM auth_code');
        $connection->query('DELETE FROM refresh_token');
        $connection->query('DELETE FROM client');
        $connection->query('SET FOREIGN_KEY_CHECKS=1');
        $connection->commit();
    }

    protected function tearDown(): void
    {
        $this->commandBus = null;
        $this->queryBus = null;
        $this->service('doctrine.orm.entity_manager')->getConnection()->rollback();
        /** @var Connection $connection */
        $connection = self::$container->get('doctrine')->getConnection();
        $connection->close();
        parent::tearDown();
    }

    /**
     * @throws \Exception
     *
     * @return string
     */
    protected function createCategory(): CategoryView
    {
        $name = 'test'.Uuid::uuid4()->toString();
        $command = Category::create($name);
        $this
            ->handle($command);
        /** @var EventCollectorListener $collector */
        $collector = $this->service(EventCollectorListener::class);
        /** @var DomainMessage[] $events */
        $events = $collector->popEvents();
        self::assertCount(1, $events);
        /** @var CategoryWasCreated $userCreatedEvent */
        $userCreatedEvent = $events[0]->getPayload();
        $manager = $this->service('doctrine.orm.entity_manager');
        $categoryReposiotry = $manager->getRepository(CategoryView::class);
        self::assertInstanceOf(CategoryWasCreated::class, $userCreatedEvent);
        $category = $categoryReposiotry->find($userCreatedEvent->getId()->toString());

        return $category;
    }

    /**
     * @throws \Exception
     *
     * @return string
     */
    protected function createUser(): string
    {
        $email = 'asd@asd.asd'.Uuid::uuid4()->toString();
        $command = User::create($email);
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
     * @throws \Exception
     *
     * @return string
     */
    protected function createPost(): PostView
    {
        $content = 'test';
        $user = $this->createUser();
        $category = $this->createCategory();
        /** @var EntityManager $manager */
        $manager = $this->service('doctrine.orm.entity_manager');
        $categoryReposiotry = $manager->getRepository(CategoryView::class);
        $category = $categoryReposiotry->find($category);
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
        $manager = $this->service('doctrine.orm.entity_manager');
        $postReposiotry = $manager->getRepository(PostView::class);
        $post = $postReposiotry->find($createPostEvent->getId()->toString());

        return $post;
    }
}

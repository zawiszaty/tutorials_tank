<?php

declare(strict_types=1);

namespace App\Tests\Application;

use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

abstract class ApplicationTestCase extends KernelTestCase
{
    protected function ask($query)
    {
        return $this->queryBus->handle($query);
    }

    protected function handle($command): void
    {
        $this->commandBus->handle($command);
    }

    protected function service(string $serviceId)
    {
        return self::$container->get($serviceId);
    }

    protected function fireTerminateEvent(): void
    {
        /** @var EventDispatcherInterface $dispatcher */
        $dispatcher = $this->service('event_dispatcher');
        $dispatcher->dispatch(
            KernelEvents::TERMINATE,
            new PostResponseEvent(
                static::$kernel,
                Request::create('/'),
                Response::create()
            )
        );
    }

    protected function setUp()
    {
        static::bootKernel();
        $this->service('doctrine.orm.entity_manager')->getConnection()->beginTransaction();
        $this->commandBus = $this->service('tactician.commandbus.command');
        $this->queryBus = $this->service('tactician.commandbus.query');
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

    protected function tearDown()
    {
        $this->commandBus = null;
        $this->queryBus = null;
        $this->service('doctrine.orm.entity_manager')->getConnection()->rollback();
    }

    /** @var CommandBus|null */
    private $commandBus;

    /** @var CommandBus|null */
    private $queryBus;
}

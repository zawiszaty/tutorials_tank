<?php

namespace App\Infrastructure\Notification\Strategy;

use App\Infrastructure\Notification\NotificationManager;
use App\Infrastructure\Notification\Strategy\Unit\CommentCreateNotification;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class NotificationAbstractFactory.
 */
class NotificationAbstractFactory
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var NotificationStrategyInterface
     */
    private $strategy;
    /**
     * @var NotificationManager
     */
    private $notificationManager;
    /**
     * @var CommentCreateNotification
     */
    private $commentCreateNotification;

    /**
     * NotificationAbstractFactory constructor.
     */
    public function __construct(ContainerInterface $container, NotificationManager $notificationManager, CommentCreateNotification $commentCreateNotification)
    {
        $this->container = $container;
        $this->notificationManager = $notificationManager;
        $this->commentCreateNotification = $commentCreateNotification;
    }

    /**
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function create(string $type, array $data): void
    {
        if ('test' !== $this->container->getParameter('APP_ENV')) {
            switch ($type) {
                case 'comment':
                    $this->commentCreateNotification->notify($data);

                    break;
            }
        }
        $this->notificationManager->create($data);
    }
}

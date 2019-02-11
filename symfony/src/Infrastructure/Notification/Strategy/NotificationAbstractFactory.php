<?php

namespace App\Infrastructure\Notification\Strategy;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Notification\NotificationFactory;
use App\Infrastructure\Notification\Query\MysqlNotificationRepository;
use App\Infrastructure\Notification\Strategy\Unit\CommentCreateNotification;
use App\Infrastructure\User\Query\Repository\MysqlUserReadModelRepository;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class NotificationAbstractFactory.
 */
class NotificationAbstractFactory
{
    /**
     * @var MysqlNotificationRepository
     */
    private $mysqlNotificationRepository;

    /**
     * @var MysqlUserReadModelRepository
     */
    private $mysqlUserReadModelRepository;

    /**
     * @var Container
     */
    private $container;

    /**
     * NotificationAbstractFactory constructor.
     *
     * @param MysqlNotificationRepository  $mysqlNotificationRepository
     * @param MysqlUserReadModelRepository $mysqlUserReadModelRepository
     */
    public function __construct(MysqlNotificationRepository $mysqlNotificationRepository, MysqlUserReadModelRepository $mysqlUserReadModelRepository, ContainerInterface $container)
    {
        $this->mysqlNotificationRepository = $mysqlNotificationRepository;
        $this->mysqlUserReadModelRepository = $mysqlUserReadModelRepository;
        $this->container = $container;
    }

    /**
     * @param string $type
     * @param array  $data
     *
     * @throws \ZMQSocketException
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function create(string $type, array $data)
    {
        if ($this->container->getParameter('APP_ENV') !== 'test') {
            switch ($type) {
                case 'comment':
                    CommentCreateNotification::notify($data);

                    break;
            }
        }
        $user = $this->mysqlUserReadModelRepository->getSingle(AggregateRootId::fromString($data['user']));
        $notification = NotificationFactory::create(json_encode($data['content']), $user->readModel, $data['type']);
        $this->mysqlNotificationRepository->add($notification);
    }
}

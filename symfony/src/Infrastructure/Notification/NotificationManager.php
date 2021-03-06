<?php

namespace App\Infrastructure\Notification;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Notification\Query\MysqlNotificationRepository;
use App\Infrastructure\Notification\Query\NotificationRepositoryElastic;
use App\Infrastructure\User\Query\Repository\MysqlUserReadModelRepository;

/**
 * Class NotificationManager.
 */
class NotificationManager
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
     * @var NotificationRepositoryElastic
     */
    private $notificationRepositoryElastic;

    /**
     * NotificationManager constructor.
     */
    public function __construct(MysqlNotificationRepository $mysqlNotificationRepository, MysqlUserReadModelRepository $mysqlUserReadModelRepository, NotificationRepositoryElastic $notificationRepositoryElastic)
    {
        $this->mysqlNotificationRepository = $mysqlNotificationRepository;
        $this->mysqlUserReadModelRepository = $mysqlUserReadModelRepository;
        $this->notificationRepositoryElastic = $notificationRepositoryElastic;
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function create(array $data): void
    {
        $user = $this->mysqlUserReadModelRepository->getSingle(AggregateRootId::fromString($data['user']));
        $notification = NotificationFactory::create(json_encode($data['content']), $user->readModel, $data['type']);
        $this->mysqlNotificationRepository->add($notification);
        $this->notificationRepositoryElastic->store($notification->serialize());
    }
}

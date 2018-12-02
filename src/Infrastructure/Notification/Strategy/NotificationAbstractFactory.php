<?php

namespace App\Infrastructure\Notification\Strategy;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Notification\NotificationFactory;
use App\Infrastructure\Notification\Query\MysqlNotificationRepository;
use App\Infrastructure\Notification\Strategy\Unit\CommentCreateNotification;
use App\Infrastructure\User\Query\Repository\MysqlUserReadModelRepository;

/**
 * Class NotificationAbstractFactory
 * @package App\Infrastructure\Notification\Strategy
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
     * NotificationAbstractFactory constructor.
     * @param MysqlNotificationRepository $mysqlNotificationRepository
     * @param MysqlUserReadModelRepository $mysqlUserReadModelRepository
     */
    public function __construct(MysqlNotificationRepository $mysqlNotificationRepository, MysqlUserReadModelRepository $mysqlUserReadModelRepository)
    {
        $this->mysqlNotificationRepository = $mysqlNotificationRepository;
        $this->mysqlUserReadModelRepository = $mysqlUserReadModelRepository;
    }

    /**
     * @param string $type
     * @param array $data
     * @throws \ZMQSocketException
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function create(string $type, array $data)
    {
        switch ($type) {
            case 'comment':
                CommentCreateNotification::notify($data);
                break;
        }
        $user = $this->mysqlUserReadModelRepository->getSingle(AggregateRootId::fromString($data['user']));
        $notification = NotificationFactory::create(json_encode($data['content']), $user->readModel, $data['type']);
        $this->mysqlNotificationRepository->add($notification);
    }
}
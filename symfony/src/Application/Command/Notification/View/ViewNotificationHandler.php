<?php

namespace App\Application\Command\Notification\View;

use App\Application\Command\CommandHandlerInterface;
use App\Infrastructure\Notification\Query\MysqlNotificationRepository;
use App\Infrastructure\Notification\Query\NotificationRepositoryElastic;

/**
 * Class ViewNotificationHandler.
 */
class ViewNotificationHandler implements CommandHandlerInterface
{
    /**
     * @var MysqlNotificationRepository
     */
    private $mysqlNotificationRepository;

    /**
     * @var NotificationRepositoryElastic
     */
    private $notificationRepositoryElastic;

    /**
     * ViewNotificationHandler constructor.
     */
    public function __construct(MysqlNotificationRepository $mysqlNotificationRepository, NotificationRepositoryElastic $notificationRepositoryElastic)
    {
        $this->mysqlNotificationRepository = $mysqlNotificationRepository;
        $this->notificationRepositoryElastic = $notificationRepositoryElastic;
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function __invoke(ViewNotificationCommand $command): void
    {
        foreach ($command->notifications as $notification) {
            $notification = $this->mysqlNotificationRepository->oneByUuid($notification);
            $notification->view();
            $this->notificationRepositoryElastic->edit($notification->serialize());
        }
        $this->mysqlNotificationRepository->apply();
    }
}

<?php

namespace App\Infrastructure\Notification\Query;

use App\Infrastructure\Notification\NotificationView;
use App\Infrastructure\Share\Query\Repository\MysqlRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class MysqlNotificationRepository
 * @package App\Infrastructure\Notification\Query
 */
class MysqlNotificationRepository extends MysqlRepository
{
    /**
     * @param NotificationView $notificationView
     */
    public function add(NotificationView $notificationView): void
    {
        $this->register($notificationView);
    }

    /**
     * MysqlCategoryReadModelRepository constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->class = NotificationView::class;
        parent::__construct($entityManager);
    }
}
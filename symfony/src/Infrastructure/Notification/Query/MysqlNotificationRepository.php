<?php

namespace App\Infrastructure\Notification\Query;

use App\Infrastructure\Notification\NotificationView;
use App\Infrastructure\Share\Query\Repository\MysqlRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class MysqlNotificationRepository.
 */
class MysqlNotificationRepository extends MysqlRepository
{
    public function add(NotificationView $notificationView): void
    {
        $this->register($notificationView);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function oneByUuid(string $id)
    {
        $qb = $this->repository
            ->createQueryBuilder('comment')
            ->where('comment.id = :id')
            ->setParameter('id', $id);

        return $this->oneOrException($qb);
    }

    /**
     * MysqlCategoryReadModelRepository constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->class = NotificationView::class;
        parent::__construct($entityManager);
    }
}

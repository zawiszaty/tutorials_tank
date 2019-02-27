<?php

/** @noinspection ALL */

namespace App\Infrastructure\User\Query\Repository;

use App\Application\Query\Collection;
use App\Application\Query\Item;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\User\ValueObject\Email;
use App\Infrastructure\Share\Query\Repository\MysqlRepository;
use App\Infrastructure\User\Query\Projections\UserView;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class MysqluserReadModelRepository.
 */
class MysqlUserReadModelRepository extends MysqlRepository
{


    public function add(UserView $userView): void
    {
        $this->register($userView);
    }

    public function delete(string $id)
    {
        $user = $this->repository->find($id);
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function oneByUuid(AggregateRootId $id)
    {
        $qb = $this->repository
            ->createQueryBuilder('user')
            ->where('user.id = :id')
            ->setParameter('id', $id->toString());

        return $this->oneOrException($qb);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function oneByEmail(Email $email)
    {
        $qb = $this->repository
            ->createQueryBuilder('user')
            ->where('user.email = :email')
            ->setParameter('email', $email->toString());

        return $this->oneOrException($qb);
    }

    /**
     * @return Collection
     */
    public function getAll(int $page, int $limit)
    {
        $qb = $this->repository
            ->createQueryBuilder('user')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);
        $model = $qb->getQuery()->execute();

        $qbCount = $this->repository
            ->createQueryBuilder('user')
            ->select('count(user.id)');
        $count = $qbCount->getQuery()->execute();
        $data = [];

        foreach ($model as $item) {
            $data[] = $item->serialize();
        }
        $collection = new Collection($page, $limit, $count[0]['1'], $data);

        return $collection;
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return Item
     */
    public function getSingle(AggregateRootId $id)
    {
        $qb = $this->repository
            ->createQueryBuilder('user')
            ->where('user.id = :id')
            ->setParameter('id', $id->toString());
        $model = $qb->getQuery()->getOneOrNullResult();

        if (!$model) {
            throw new NotFoundHttpException();
        }

        return new Item($model);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return mixed
     */
    public function getByToken(string $token): UserView
    {
        $qb = $this->repository
            ->createQueryBuilder('user')
            ->where('user.confirmationToken = :confirmationToken')
            ->setParameter('confirmationToken', $token);
        $model = $qb->getQuery()->getOneOrNullResult();

        if (!$model) {
            throw new NotFoundHttpException();
        }

        return $model;
    }

    /**
     * MysqlUserReadModelRepository constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->class = UserView::class;
        parent::__construct($entityManager);
    }
}

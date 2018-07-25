<?php

namespace App\Infrastructure\Category\Query\Mysql;

use App\Application\Query\Item;
use App\Domain\Common\ValueObject\AggregatRootId;
use App\Infrastructure\Category\Query\Projections\CategoryView;
use App\Infrastructure\Share\Query\Repository\MysqlRepository;
use Doctrine\ORM\EntityManagerInterface;

class MysqlCategoryReadModelRepository extends MysqlRepository
{
    public function add(CategoryView $categoryView): void
    {
        $this->register($categoryView);
    }

    public function oneByUuid(AggregatRootId $id)
    {
        $qb = $this->repository
            ->createQueryBuilder('category')
            ->where('category.id = :id')
            ->setParameter('id', $id->toString())
        ;

        return $this->oneOrException($qb);
    }

    public function getAll()
    {
        $qb =  $this->repository
            ->createQueryBuilder('category')
            ->where('category.deleted = :deleted')
            ->setParameter('deleted', false);
        $model = $qb->getQuery();

        return $model;
    }

    /**
     * @param AggregatRootId $id
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getSingle(AggregatRootId $id)
    {
        $qb = $this->repository
            ->createQueryBuilder('category')
            ->where('category.id = :id')
            ->setParameter('id', $id->toString())
        ;
        $model = $qb->getQuery()->getOneOrNullResult();

        return new Item($model);
    }

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->class = CategoryView::class;
        parent::__construct($entityManager);
    }
}
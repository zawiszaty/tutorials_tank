<?php

namespace App\Infrastructure\Category\Query\Mysql;

use App\Application\Query\Collection;
use App\Application\Query\Item;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Category\Query\Projections\CategoryView;
use App\Infrastructure\Share\Query\Repository\MysqlRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class MysqlCategoryReadModelRepository
 * @package App\Infrastructure\Category\Query\Mysql
 */
class MysqlCategoryReadModelRepository extends MysqlRepository
{
    /**
     * @param CategoryView $categoryView
     */
    public function add(CategoryView $categoryView): void
    {
        $this->register($categoryView);
    }

    /**
     * @param AggregateRootId $id
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function oneByUuid(AggregateRootId $id)
    {
        $qb = $this->repository
            ->createQueryBuilder('category')
            ->where('category.id = :id')
            ->setParameter('id', $id->toString())
        ;

        return $this->oneOrException($qb);
    }

    /**
     * @return Collection
     */
    public function getAll()
    {
        $qb =  $this->repository
            ->createQueryBuilder('category')
            ->where('category.deleted = :deleted')
            ->setParameter('deleted', false);
        $model = $qb->getQuery()->execute();

        $qbCount =  $this->repository
            ->createQueryBuilder('category')
            ->select('count(category.id)')
            ->where('category.deleted = :deleted')
            ->setParameter('deleted', false);
        $count = $qbCount->getQuery()->execute();
        $collection = new Collection($model, $count[0]['1']);

        return $collection;
    }

    /**
     * @param AggregateRootId $id
     * @return Item
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getSingle(AggregateRootId $id)
    {
        $qb = $this->repository
            ->createQueryBuilder('category')
            ->where('category.id = :id')
            ->setParameter('id', $id->toString())
        ;
        $model = $qb->getQuery()->getOneOrNullResult();

        return new Item($model);
    }

    /**
     * MysqlCategoryReadModelRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->class = CategoryView::class;
        parent::__construct($entityManager);
    }
}
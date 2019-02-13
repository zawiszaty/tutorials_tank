<?php

namespace App\Infrastructure\Category\Query\Mysql;

use App\Application\Query\Collection;
use App\Application\Query\Item;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Category\Query\Projections\CategoryView;
use App\Infrastructure\Share\Query\Repository\MysqlRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class MysqlCategoryReadModelRepository.
 */
class MysqlCategoryReadModelRepository extends MysqlRepository
{
    public function add(CategoryView $categoryView): void
    {
        $this->register($categoryView);
    }

    public function delete(string $id)
    {
        $category = $this->repository->find($id);
        $this->entityManager->remove($category);
        $this->entityManager->flush();
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function oneByUuid(AggregateRootId $id)
    {
        $qb = $this->repository
            ->createQueryBuilder('category')
            ->where('category.id = :id')
            ->setParameter('id', $id->toString());

        return $this->oneOrException($qb);
    }

    /**
     * @return Collection
     */
    public function getAll(int $page, int $limit)
    {
        $qb = $this->repository
            ->createQueryBuilder('category')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);
        $model = $qb->getQuery()->execute();

        $qbCount = $this->repository
            ->createQueryBuilder('category')
            ->select('count(category.id)');
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
            ->createQueryBuilder('category')
            ->where('category.id = :id')
            ->setParameter('id', $id->toString());
        $model = $qb->getQuery()->getOneOrNullResult();

        if (!$model) {
            throw new NotFoundHttpException();
        }

        return new Item($model);
    }

    /**
     * MysqlCategoryReadModelRepository constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->class = CategoryView::class;
        parent::__construct($entityManager);
    }
}

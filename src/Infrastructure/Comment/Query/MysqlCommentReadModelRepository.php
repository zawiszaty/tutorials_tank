<?php

namespace App\Infrastructure\Comment\Query;

use App\Application\Query\Collection;
use App\Application\Query\Item;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Comment\Query\Projections\CommentView;
use App\Infrastructure\Share\Query\Repository\MysqlRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class MysqlCategoryReadModelRepository.
 */
class MysqlCommentReadModelRepository extends MysqlRepository
{
    /**
     * @param CommentView $CommentView
     */
    public function add(CommentView $CommentView): void
    {
        $this->register($CommentView);
    }

    /**
     * @param string $id
     */
    public function delete(string $id)
    {
        $category = $this->repository->find($id);
        $this->entityManager->remove($category);
        $this->entityManager->flush();
    }

    /**
     * @param AggregateRootId $id
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return mixed
     */
    public function oneByUuid(AggregateRootId $id)
    {
        $qb = $this->repository
            ->createQueryBuilder('comment')
            ->where('comment.id = :id')
            ->setParameter('id', $id->toString());

        return $this->oneOrException($qb);
    }

    /**
     * @param int $page
     * @param int $limit
     *
     * @return Collection
     */
    public function getAll(int $page, int $limit)
    {
        $qb = $this->repository
            ->createQueryBuilder('comment')
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
     * @param AggregateRootId $id
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return Item
     */
    public function getSingle(AggregateRootId $id)
    {
        $qb = $this->repository
            ->createQueryBuilder('comment')
            ->where('comment.id = :id')
            ->setParameter('id', $id->toString());
        $model = $qb->getQuery()->getOneOrNullResult();

        if (!$model) {
            throw new NotFoundHttpException();
        }

        return new Item($model);
    }

    /**
     * MysqlCategoryReadModelRepository constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->class = CommentView::class;
        parent::__construct($entityManager);
    }
}

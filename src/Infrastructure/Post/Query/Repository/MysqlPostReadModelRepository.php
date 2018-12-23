<?php

namespace App\Infrastructure\Post\Query\Repository;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Post\Query\Projections\PostView;
use App\Infrastructure\Share\Query\Repository\MysqlRepository;
use Doctrine\ORM\EntityManagerInterface;

class MysqlPostReadModelRepository extends MysqlRepository
{
    /**
     * @param PostView $postView
     */
    public function add(PostView $postView): void
    {
        $this->register($postView);
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
            ->createQueryBuilder('post')
            ->where('post.id = :id')
            ->setParameter('id', $id->toString());

        return $this->oneOrException($qb);
    }

    /**
     * @param string $id
     */
    public function delete(string $id)
    {
        $post = $this->repository->find($id);
        $this->entityManager->remove($post);
        $this->entityManager->flush();
    }

    /**
     * MysqlUserReadModelRepository constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->class = PostView::class;
        parent::__construct($entityManager);
    }
}

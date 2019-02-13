<?php

namespace App\Infrastructure\Post\Query\Repository;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Post\Query\Projections\PostView;
use App\Infrastructure\Share\Query\Repository\MysqlRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class MysqlPostReadModelRepository.
 */
class MysqlPostReadModelRepository extends MysqlRepository
{
    public function add(PostView $postView): void
    {
        $this->register($postView);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function oneByUuid(AggregateRootId $id)
    {
        $qb = $this->repository
            ->createQueryBuilder('post')
            ->where('post.id = :id')
            ->setParameter('id', $id->toString());

        return $this->oneOrException($qb);
    }

    public function delete(string $id)
    {
        $post = $this->repository->find($id);
        $this->entityManager->remove($post);
        $this->entityManager->flush();
    }

    /**
     * MysqlUserReadModelRepository constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->class = PostView::class;
        parent::__construct($entityManager);
    }
}

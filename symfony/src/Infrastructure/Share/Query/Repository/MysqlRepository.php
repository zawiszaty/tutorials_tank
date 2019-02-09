<?php

declare(strict_types=1);

namespace App\Infrastructure\Share\Query\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class MysqlRepository.
 */
abstract class MysqlRepository
{
    /**
     * @param $model
     */
    public function register($model): void
    {
        $this->entityManager->persist($model);
        $this->apply();
    }

    public function apply(): void
    {
        $this->entityManager->flush();
    }

    /**
     * @param QueryBuilder $queryBuilder
     *
     * @throws NotFoundHttpException
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return mixed
     */
    protected function oneOrException(QueryBuilder $queryBuilder)
    {
        $model = $queryBuilder
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $model) {
            throw new NotFoundHttpException();
        }

        return $model;
    }

    /**
     * @param string $model
     */
    private function setRepository(string $model): void
    {
        /** @var EntityRepository $objectRepository */
        $objectRepository = $this->entityManager->getRepository($model);
        $this->repository = $objectRepository;
    }

    /**
     * MysqlRepository constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->setRepository($this->class);
    }

    /** @var string */
    protected $class;

    /** @var EntityRepository */
    protected $repository;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;
}

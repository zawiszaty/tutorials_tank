<?php

declare(strict_types=1);

namespace App\Infrastructure\Share\Query\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

abstract class MysqlRepository
{
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
     * @return mixed
     *
     * @throws NotFoundException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function oneOrException(QueryBuilder $queryBuilder)
    {
        $model = $queryBuilder
            ->getQuery()
            ->getOneOrNullResult()
        ;

        if (null === $model) {
            throw new \Exception('nie działa');
        }

        return $model;
    }

    private function setRepository(string $model): void
    {
        /** @var EntityRepository $objectRepository */
        $objectRepository = $this->entityManager->getRepository($model);
        $this->repository = $objectRepository;
    }

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
    private $entityManager;
}
<?php

namespace App\Infrastructure\Category;

use App\Domain\Category\Event\CategoryWasCreated;
use App\Domain\Category\Event\CategoryWasDeleted;
use App\Domain\Category\Event\NameWasChanged;
use App\Infrastructure\Category\Query\Mysql\MysqlCategoryReadModelRepository;
use App\Infrastructure\Category\Query\Projections\CategoryView;
use App\Infrastructure\Category\Repository\CategoryRepositoryElastic;
use Broadway\ReadModel\Projector;

/**
 * Class CategoryReadProjectionFactory.
 */
class CategoryReadProjectionFactory extends Projector
{
    /**
     * @var MysqlCategoryReadModelRepository
     */
    private $repository;
    /**
     * @var CategoryRepositoryElastic
     */
    private $categoryRepositoryElastic;

    /**
     * @param CategoryWasCreated $categoryWasCreated
     */
    public function applyCategoryWasCreated(CategoryWasCreated $categoryWasCreated)
    {
        $categoryView = CategoryView::fromSerializable($categoryWasCreated);
        $this->repository->add($categoryView);
    }

    /**
     * @param NameWasChanged $nameWasChanged
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function applyNameWasChanged(NameWasChanged $nameWasChanged): void
    {
        /** @var CategoryView $aggregateParams */
        $aggregateParams = $this->repository->oneByUuid($nameWasChanged->getId());
        $aggregateParams->changeName($nameWasChanged->getName()->toString());
        $this->repository->apply();
    }

    /**
     * @param CategoryWasDeleted $categoryWasDeleted
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function applyCategoryWasDeleted(CategoryWasDeleted $categoryWasDeleted): void
    {
        /** @var CategoryView $aggregateParams */
        $aggregateParams = $this->repository->oneByUuid($categoryWasDeleted->getId());
        $aggregateParams->delete();
        $this->repository->delete($categoryWasDeleted->getId());
    }

    /**
     * CategoryReadProjectionFactory constructor.
     *
     * @param MysqlCategoryReadModelRepository $repository
     */
    public function __construct(MysqlCategoryReadModelRepository $repository, CategoryRepositoryElastic $categoryRepositoryElastic)
    {
        $this->repository = $repository;
        $this->categoryRepositoryElastic = $categoryRepositoryElastic;
    }
}

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
final class CategoryReadProjectionFactory extends Projector
{
    /**
     * @var MysqlCategoryReadModelRepository
     */
    private $repository;

    /**
     * @var CategoryRepositoryElastic
     */
    private $categoryRepositoryElastic;

    public function applyCategoryWasCreated(CategoryWasCreated $categoryWasCreated): void
    {
        $categoryView = CategoryView::fromSerializable($categoryWasCreated);
        $this->repository->add($categoryView);
        $this->categoryRepositoryElastic->store($categoryWasCreated);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function applyNameWasChanged(NameWasChanged $nameWasChanged): void
    {
        /** @var CategoryView $aggregateParams */
        $aggregateParams = $this->repository->oneByUuid($nameWasChanged->getId());
        $aggregateParams->changeName($nameWasChanged->getName()->toString());
        $this->repository->apply();
        $this->categoryRepositoryElastic->edit($aggregateParams->serialize());
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function applyCategoryWasDeleted(CategoryWasDeleted $categoryWasDeleted): void
    {
        /** @var CategoryView $aggregateParams */
        $aggregateParams = $this->repository->oneByUuid($categoryWasDeleted->getId());
        $aggregateParams->delete();
        $this->repository->delete($categoryWasDeleted->getId());
        $this->categoryRepositoryElastic->deleteRow($aggregateParams->getId());
    }

    /**
     * CategoryReadProjectionFactory constructor.
     */
    public function __construct(MysqlCategoryReadModelRepository $repository, CategoryRepositoryElastic $categoryRepositoryElastic)
    {
        $this->repository = $repository;
        $this->categoryRepositoryElastic = $categoryRepositoryElastic;
    }
}

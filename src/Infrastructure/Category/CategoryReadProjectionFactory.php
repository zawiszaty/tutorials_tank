<?php

namespace App\Infrastructure\Category;

use App\Domain\Category\Category;
use App\Domain\Category\Event\CategoryWasCreated;
use App\Domain\Category\Event\CategoryWasDeleted;
use App\Domain\Category\Event\NameWasChanged;
use App\Infrastructure\Category\Query\Mysql\MysqlCategoryReadModelRepository;
use App\Infrastructure\Category\Query\Projections\CategoryView;
use App\Infrastructure\Category\Repository\CategoryRepositoryElastic;
use Broadway\ReadModel\Projector;

/**
 * Class CategoryReadProjectionFactory
 * @package App\Infrastructure\Category
 */
class CategoryReadProjectionFactory extends Projector
{
    /**
     * @var CategoryRepositoryElastic
     */
    private $categoryRepositoryElastic;

    /**
     * @var MysqlCategoryReadModelRepository
     */
    private $repository;

    /**
     * @param CategoryWasCreated $categoryWasCreated
     * @throws \Assert\AssertionFailedException
     */
    public function applyCategoryWasCreated(CategoryWasCreated $categoryWasCreated)
    {
        $readModel = CategoryView::fromSerializable($categoryWasCreated);
        $this->categoryRepositoryElastic->store($categoryWasCreated);
    }

    /**
     * @param NameWasChanged $nameWasChanged
     * @throws \Assert\AssertionFailedException
     */
    public function applyNameWasChanged(NameWasChanged $nameWasChanged): void
    {
        $aggregateParams = $this->categoryRepositoryElastic->get($nameWasChanged->getId()->toString());
        $category = Category::fromString($aggregateParams['_source']);
        $category->changeName($nameWasChanged->getName());
        $this->categoryRepositoryElastic->store($nameWasChanged);
        $this->repository->apply();
    }

    /**
     * @param CategoryWasDeleted $categoryWasDeleted
     * @throws \Assert\AssertionFailedException
     */
    public function applyCategoryWasDeleted(CategoryWasDeleted $categoryWasDeleted): void
    {
        $aggregateParams = $this->categoryRepositoryElastic->get($categoryWasDeleted->getId()->toString());
        $category = Category::fromString($aggregateParams['_source']);
        $category->delete();
        $this->categoryRepositoryElastic->deleteRow($categoryWasDeleted->getId()->toString());
    }

    /**
     * CategoryReadProjectionFactory constructor.
     * @param MysqlCategoryReadModelRepository $repository
     * @param CategoryRepositoryElastic $categoryRepositoryElastic
     */
    public function __construct(MysqlCategoryReadModelRepository $repository, CategoryRepositoryElastic $categoryRepositoryElastic)
    {
        $this->repository = $repository;
        $this->categoryRepositoryElastic = $categoryRepositoryElastic;
    }
}
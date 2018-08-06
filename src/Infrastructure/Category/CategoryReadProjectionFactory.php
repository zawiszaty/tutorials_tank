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
     * @param CategoryWasCreated $categoryWasCreated
     * @throws \Assert\AssertionFailedException
     */
    public function applyCategoryWasCreated(CategoryWasCreated $categoryWasCreated)
    {
        $readModel = CategoryView::fromSerializable($categoryWasCreated);
        $this->repository->add($readModel);
        $this->categoryRepositoryElastic->store($readModel);
    }

    /**
     * @param NameWasChanged $nameWasChanged
     */
    public function applyNameWasChanged(NameWasChanged $nameWasChanged)
    {
        $readModel = $this->repository->oneByUuid($nameWasChanged->getId());
        $readModel->changeName($nameWasChanged->getName());
        $this->categoryRepositoryElastic->store($nameWasChanged);
        $this->repository->apply();
    }

    /**
     * @param CategoryWasDeleted $categoryWasDeleted
     */
    public function applyCategoryWasDeleted(CategoryWasDeleted $categoryWasDeleted)
    {
        $readModel = $this->repository->oneByUuid($categoryWasDeleted->getId());
        $readModel->delete();
        $this->categoryRepositoryElastic->deleteRow($categoryWasDeleted->getId());
        $this->repository->apply();
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

    /**
     * @var MysqlCategoryReadModelRepository
     */
    private $repository;
}
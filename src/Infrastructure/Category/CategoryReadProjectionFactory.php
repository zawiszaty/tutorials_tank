<?php

namespace App\Infrastructure\Category;

use App\Domain\Category\Event\CategoryWasCreated;
use App\Domain\Category\Event\NameWasChanged;
use App\Infrastructure\Category\Query\Mysql\MysqlCategoryReadModelRepository;
use App\Infrastructure\Category\Query\Projections\CategoryView;
use Broadway\ReadModel\Projector;

class CategoryReadProjectionFactory extends Projector
{
    public function applyCategoryWasCreated(CategoryWasCreated $categoryWasCreated)
    {
        $readModel = CategoryView::fromSerializable($categoryWasCreated);

        $this->repository->add($readModel);
    }

    public function applyNameWasChanged(NameWasChanged $nameWasChanged)
    {
        $readModel = $this->repository->oneByUuid($nameWasChanged->getId());
        $readModel->changeName($nameWasChanged->getName());
        $this->repository->apply();
    }

    public function __construct(MysqlCategoryReadModelRepository $repository)
    {
        $this->repository = $repository;
    }

    private $repository;
}
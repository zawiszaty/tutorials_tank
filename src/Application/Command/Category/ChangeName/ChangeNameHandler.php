<?php

namespace App\Application\Command\Category\ChangeName;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Category\Repository\CategoryReposiotryInterface;

class ChangeNameHandler implements CommandHandlerInterface
{
    /**
     * @var CategoryReposiotryInterface
     */
    private $categoryReposiotry;

    public function __construct(CategoryReposiotryInterface $categoryReposiotry)
    {
        $this->categoryReposiotry = $categoryReposiotry;
    }

    public function __invoke(ChangeNameCommand $command): void
    {
        $category = $this->categoryReposiotry->get($command->getId());
        $category->changeName($command->getName());
        $this->categoryReposiotry->store($category);
    }
}
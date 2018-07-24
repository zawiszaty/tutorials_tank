<?php

namespace App\Application\Command\Category\Delete;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Category\Repository\CategoryReposiotryInterface;

/**
 * Class DeleteCategoryHandler
 * @package App\Application\Command\Category\Delete
 */
class DeleteCategoryHandler implements CommandHandlerInterface
{
    /**
     * @var CategoryReposiotryInterface
     */
    private $categoryReposiotry;

    /**
     * DeleteCategoryHandler constructor.
     * @param CategoryReposiotryInterface $categoryReposiotry
     */
    public function __construct(CategoryReposiotryInterface $categoryReposiotry)
    {
        $this->categoryReposiotry = $categoryReposiotry;
    }

    /**
     * @param DeleteCategoryCommand $categoryCommand
     * @throws \Assert\AssertionFailedException
     */
    public function __invoke(DeleteCategoryCommand $categoryCommand): void
    {
        $category = $this->categoryReposiotry->get($categoryCommand->getId());
        $category->delete();
        $this->categoryReposiotry->store($category);
    }
}
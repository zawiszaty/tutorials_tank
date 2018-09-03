<?php

namespace App\Application\Command\Category\Delete;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Category\Repository\CategoryRepositoryInterface;

/**
 * Class DeleteCategoryHandler.
 */
class DeleteCategoryHandler implements CommandHandlerInterface
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * DeleteCategoryHandler constructor.
     *
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param DeleteCategoryCommand $categoryCommand
     *
     */
    public function __invoke(DeleteCategoryCommand $categoryCommand): void
    {
        $category = $this->categoryRepository->get($categoryCommand->getId());
        $category->delete();
        $this->categoryRepository->store($category);
    }
}

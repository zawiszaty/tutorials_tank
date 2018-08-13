<?php

namespace App\Application\Command\Category\ChangeName;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Category\Repository\CategoryRepositoryInterface;

/**
 * Class ChangeNameHandler.
 */
class ChangeNameHandler implements CommandHandlerInterface
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * ChangeNameHandler constructor.
     *
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param ChangeNameCommand $command
     */
    public function __invoke(ChangeNameCommand $command): void
    {
        $category = $this->categoryRepository->get($command->getId());
        $category->changeName($command->getName());
        $this->categoryRepository->store($category);
    }
}

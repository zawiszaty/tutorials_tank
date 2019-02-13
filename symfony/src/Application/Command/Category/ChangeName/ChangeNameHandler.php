<?php

namespace App\Application\Command\Category\ChangeName;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Category\Repository\CategoryRepositoryInterface;
use App\Domain\Category\ValueObject\Name;
use App\Domain\Common\ValueObject\AggregateRootId;

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
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    public function __invoke(ChangeNameCommand $command): void
    {
        $category = $this->categoryRepository->get(AggregateRootId::fromString($command->id));
        $category->changeName(Name::fromString($command->name));
        $this->categoryRepository->store($category);
    }
}

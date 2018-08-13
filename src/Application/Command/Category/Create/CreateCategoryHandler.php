<?php

namespace App\Application\Command\Category\Create;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Category\Exception\CategoryCreateException;
use App\Domain\Category\Factory\CategoryFactory;
use App\Domain\Category\Repository\CategoryRepositoryInterface;
use App\Domain\Category\ValueObject\Name;

/**
 * Class CreateCategoryHandler.
 */
class CreateCategoryHandler implements CommandHandlerInterface
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * CreateCategoryHandler constructor.
     *
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param CreateCategoryCommand $categoryCommand
     *
     * @throws \Assert\AssertionFailedException
     * @throws \Exception
     */
    public function __invoke(CreateCategoryCommand $categoryCommand)
    {
        $aggregateRoot = CategoryFactory::create(Name::fromString($categoryCommand->getName()));
        $this->categoryRepository->store($aggregateRoot);

        throw new CategoryCreateException($aggregateRoot->getId());
    }
}

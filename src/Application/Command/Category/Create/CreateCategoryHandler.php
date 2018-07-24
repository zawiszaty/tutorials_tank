<?php

namespace App\Application\Command\Category\Create;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Category\Factory\CategoryFactory;
use App\Domain\Category\Repository\CategoryReposiotryInterface;
use App\Domain\Category\ValueObject\Name;

/**
 * Class CreateCategoryHandler
 * @package App\Application\Command\Category\Create
 */
class CreateCategoryHandler implements CommandHandlerInterface
{
    /**
     * @var CategoryReposiotryInterface
     */
    private $categoryReposiotry;

    /**
     * CreateCategoryHandler constructor.
     * @param CategoryReposiotryInterface $categoryReposiotry
     */
    public function __construct(CategoryReposiotryInterface $categoryReposiotry)
    {
        $this->categoryReposiotry = $categoryReposiotry;
    }

    /**
     * @param CreateCategoryCommand $categoryCommand
     * @throws \Assert\AssertionFailedException
     * @throws \Exception
     */
    public function __invoke(CreateCategoryCommand $categoryCommand)
    {
        $aggregateRoot = CategoryFactory::create(Name::fromString($categoryCommand->getName()));
        $this->categoryReposiotry->store($aggregateRoot);

        throw  new \Exception($aggregateRoot->getId());
    }
}
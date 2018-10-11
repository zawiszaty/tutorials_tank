<?php

namespace App\Application\Query\Category\GetAll;

use App\Application\Query\Collection;
use App\Application\Query\QueryHandlerInterface;
use App\Infrastructure\Category\Query\Mysql\MysqlCategoryReadModelRepository;
use App\Infrastructure\Category\Repository\CategoryFosElasticaRepository;
use App\Infrastructure\Category\Repository\CategoryRepositoryElastic;

/**
 * Class GetAllHandler.
 */
class GetAllHandler implements QueryHandlerInterface
{
    /**
     * @var MysqlCategoryReadModelRepository
     */
    private $modelRepository;

    /**
     * @var CategoryRepositoryElastic
     */
    private $categoryRepositoryElastic;

    /**
     * GetAllHandler constructor.
     *
     * @param MysqlCategoryReadModelRepository $modelRepository
     * @param CategoryRepositoryElastic $categoryRepositoryElastic
     */
    public function __construct(MysqlCategoryReadModelRepository $modelRepository, CategoryRepositoryElastic $categoryRepositoryElastic)
    {
        $this->modelRepository = $modelRepository;
        $this->categoryRepositoryElastic = $categoryRepositoryElastic;
    }

    /**
     * @param GetAllCommand $command
     *
     * @return \App\Application\Query\Collection
     */
    public function __invoke(GetAllCommand $command)
    {
        $data = $this->categoryRepositoryElastic->page($command->getPage(), $command->getLimit(), $command->getQuery());

        return $data;
    }
}

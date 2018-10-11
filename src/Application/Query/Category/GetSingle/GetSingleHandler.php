<?php

namespace App\Application\Query\Category\GetSingle;

use App\Application\Query\QueryHandlerInterface;
use App\Infrastructure\Category\Query\Mysql\MysqlCategoryReadModelRepository;
use App\Infrastructure\Category\Repository\CategoryRepositoryElastic;

/**
 * Class GetSingleHandler.
 */
class GetSingleHandler implements QueryHandlerInterface
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
     * @param CategoryRepositoryElastic        $categoryRepositoryElastic
     */
    public function __construct(MysqlCategoryReadModelRepository $modelRepository, CategoryRepositoryElastic $categoryRepositoryElastic)
    {
        $this->modelRepository = $modelRepository;
        $this->categoryRepositoryElastic = $categoryRepositoryElastic;
    }

    /**
     * @param GetSingleCommand $command
     *
     * @return mixed
     */
    public function __invoke(GetSingleCommand $command)
    {
        $model = $this->categoryRepositoryElastic->get($command->getId());

        return $model['_source'];
    }
}

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
     */
    public function __construct(MysqlCategoryReadModelRepository $modelRepository, CategoryRepositoryElastic $categoryRepositoryElastic)
    {
        $this->modelRepository = $modelRepository;
        $this->categoryRepositoryElastic = $categoryRepositoryElastic;
    }

    public function __invoke(GetSingleCommand $command)
    {
        $model = $this->categoryRepositoryElastic->get($command->getId());

        return $model['_source'];
    }
}

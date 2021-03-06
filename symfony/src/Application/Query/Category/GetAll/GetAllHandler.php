<?php

namespace App\Application\Query\Category\GetAll;

use App\Application\Query\QueryHandlerInterface;
use App\Infrastructure\Category\Query\Mysql\MysqlCategoryReadModelRepository;
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
     */
    public function __construct(MysqlCategoryReadModelRepository $modelRepository, CategoryRepositoryElastic $categoryRepositoryElastic)
    {
        $this->modelRepository = $modelRepository;
        $this->categoryRepositoryElastic = $categoryRepositoryElastic;
    }

    /**
     * @return \App\Application\Query\Collection
     */
    public function __invoke(GetAllCommand $command)
    {
        if ($command->getQuery()) {
            $query = [
                'query' => [
                    'wildcard' => [
                        'name' => '*' . $command->getQuery() . '*',
                    ],
                ],
            ];
        } else {
            $query = [];
        }

        $data = $this->categoryRepositoryElastic->page($command->getPage(), $command->getLimit(), $query);

//        $data = $this->modelRepository->getAll($command->getPage(), $command->getLimit());

        return $data;
    }
}

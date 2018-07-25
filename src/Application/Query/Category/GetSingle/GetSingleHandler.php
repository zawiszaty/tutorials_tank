<?php

namespace App\Application\Query\Category\GetSingle;

use App\Application\Query\QueryHandlerInterface;
use App\Infrastructure\Category\Query\Mysql\MysqlCategoryReadModelRepository;

/**
 * Class GetSingleHandler
 * @package App\Application\Query\Category\GetSingle
 */
class GetSingleHandler implements QueryHandlerInterface
{
    /**
     * @var MysqlCategoryReadModelRepository
     */
    private $modelRepository;

    /**
     * GetAllHandler constructor.
     * @param MysqlCategoryReadModelRepository $modelRepository
     */
    public function __construct(MysqlCategoryReadModelRepository $modelRepository)
    {
        $this->modelRepository = $modelRepository;
    }

    /**
     * @param GetSingleCommand $command
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function __invoke(GetSingleCommand $command)
    {
        $model = $this->modelRepository->getSingle($command->getId());

        return $model;
    }
}
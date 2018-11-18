<?php

namespace App\Application\Query\Message\GetAll;

use App\Application\Query\Collection;
use App\Application\Query\QueryHandlerInterface;
use App\Infrastructure\Message\Query\MessageRepositoryElastic;

class GetAllHandler implements QueryHandlerInterface
{
    /**
     * @var MessageRepositoryElastic
     */
    private $repositoryElastic;
    /**
     * @var DataBuilder
     */
    private $dataBuilder;

    public function __construct(MessageRepositoryElastic $repositoryElastic, DataBuilder $dataBuilder)
    {
        $this->repositoryElastic = $repositoryElastic;
        $this->dataBuilder = $dataBuilder;
    }

    public function __invoke(GetAllCommand $command)
    {
        $data = $this->repositoryElastic->messageByCreatedAt($command->getPage(), $command->getLimit(), $command->getQuery());
        $total = $data->total;
        $data = $this->dataBuilder->build($data->data);
        return new Collection($command->getPage(), $command->getLimit(), $total, $data);
    }
}
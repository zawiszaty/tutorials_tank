<?php

namespace App\Application\Query\Message\GetAllMessageSender;

use App\Application\Query\Collection;
use App\Application\Query\QueryHandlerInterface;
use App\Infrastructure\Message\Query\MessageRepositoryElastic;

/**
 * Class GetAllMessageSenderHandler
 *
 * @package App\Application\Query\Message\GetAllMessageSender
 */
class GetAllMessageSenderHandler implements QueryHandlerInterface
{
    /**
     * @var MessageRepositoryElastic
     */
    private $repositoryElastic;

    /**
     * @var DataBuilder
     */
    private $dataBuilder;

    /**
     * GetAllHandler constructor.
     */
    public function __construct(MessageRepositoryElastic $repositoryElastic, DataBuilder $dataBuilder)
    {
        $this->repositoryElastic = $repositoryElastic;
        $this->dataBuilder = $dataBuilder;
    }

    /**
     * @return Collection
     */
    public function __invoke(GetAllMessageSenderCommand $command): Collection
    {
            $query = [
                'query' => [
                    'bool' => [
                        'should' => [
                            [
                                'match' => [
                                    'recipient' => $command->getUser(),
                                ],
                            ],
                            [
                                'match' => [
                                    'sender' => $command->getUser(),
                                ],
                            ],
                        ],
                    ],
                ],
            ];

        $data = $this->repositoryElastic->messageByCreatedAt($command->getPage(), $command->getLimit(), $query);
        $total = $data->total;
        $data = $this->dataBuilder->build(array_unique($data->data,SORT_REGULAR), $command->getUser());

        return new Collection($command->getPage(), $command->getLimit(), $total, $data);
    }
}
<?php

namespace App\Application\Query\Message\GetAll;

use App\Application\Query\Collection;
use App\Application\Query\QueryHandlerInterface;
use App\Infrastructure\Message\Query\MessageRepositoryElastic;

/**
 * Class GetAllHandler.
 */
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
    public function __invoke(GetAllCommand $command)
    {
        $query = [
            'query' => [
                'bool' => [
                    'should' => [
                     [
                         'bool' => [
                             'must' => [
                                 [
                                     'match' => [
                                         'recipient' => $command->getRecipient(),
                                     ],
                                 ],
                                 [
                                     'match' => [
                                         'sender' => $command->getUser(),
                                     ],
                                 ],
                             ]
                         ]
                     ],
                        [
                            'bool' => [
                                'must' => [
                                    [
                                        'match' => [
                                            'recipient' => $command->getUser(),
                                        ],
                                    ],
                                    [
                                        'match' => [
                                            'sender' => $command->getRecipient(),
                                        ],
                                    ],
                                ]
                            ]
                        ],
                    ],
                ]
            ],
        ];

        $data = $this->repositoryElastic->messageByCreatedAt($command->getPage(), $command->getLimit(), $query);
        $total = $data->total;
        $data = $this->dataBuilder->build($data->data);

        return new Collection($command->getPage(), $command->getLimit(), $total, $data);
    }
}

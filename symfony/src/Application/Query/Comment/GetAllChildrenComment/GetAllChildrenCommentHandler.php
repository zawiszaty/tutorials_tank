<?php

namespace App\Application\Query\Comment\GetAllChildrenComment;

use App\Application\Query\Collection;
use App\Application\Query\QueryHandlerInterface;
use App\Infrastructure\Comment\Query\CommentRepositoryElastic;

/**
 * Class GetAllChildrenCommentHandler.
 */
class GetAllChildrenCommentHandler implements QueryHandlerInterface
{
    /**
     * @var CommentRepositoryElastic
     */
    private $repositoryElastic;

    /**
     * @var CommentDataBuilder
     */
    private $builder;

    /**
     * GetAllPostCommentHandler constructor.
     */
    public function __construct(CommentRepositoryElastic $repositoryElastic, CommentDataBuilder $builder)
    {
        $this->repositoryElastic = $repositoryElastic;
        $this->builder = $builder;
    }

    /**
     * @return \App\Application\Query\Collection
     */
    public function __invoke(GetAllChildrenCommentCommand $command)
    {
        $query = [
            'query' => [
                'bool' => [
                    'must' => [
                        [
                            'match' => [
                                'parrentComment' => $command->getParrentComment(),
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $data = $this->repositoryElastic->commentByCreatedAt($command->getPage(), $command->getLimit(), $query);
        $total = $data->total;
        $data = $this->builder->build($data->data);

        return new Collection($command->getPage(), $command->getLimit(), $total, $data);
    }
}

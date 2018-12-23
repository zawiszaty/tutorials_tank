<?php

namespace App\Application\Query\Comment\GetAllPostComment;

use App\Application\Query\Collection;
use App\Application\Query\QueryHandlerInterface;
use App\Infrastructure\Comment\Query\CommentRepositoryElastic;

/**
 * Class GetAllPostCommentHandler.
 */
class GetAllPostCommentHandler implements QueryHandlerInterface
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
     *
     * @param CommentRepositoryElastic $repositoryElastic
     * @param CommentDataBuilder $builder
     */
    public function __construct(CommentRepositoryElastic $repositoryElastic, CommentDataBuilder $builder)
    {
        $this->repositoryElastic = $repositoryElastic;
        $this->builder = $builder;
    }

    /**
     * @param GetAllPostCommentCommand $command
     *
     * @return \App\Application\Query\Collection
     */
    public function __invoke(GetAllPostCommentCommand $command)
    {
        $query = [
            'query' => [
                'bool' => [
                    'should' => [
                        [
                            'match' => [
                                'post' => $command->getPost(),
                            ],
                        ],
                    ],
                    'must_not' => [
                        'exists' => [
                            'field' => 'parrentComment',
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

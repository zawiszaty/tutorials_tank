<?php

namespace App\Application\Query\Comment\GetAllChildrenComment;

use App\Application\Query\Collection;
use App\Application\Query\Comment\GetAllPostComment\GetAllPostCommentCommand;
use App\Application\Query\QueryHandlerInterface;
use App\Infrastructure\Comment\Query\CommentRepositoryElastic;

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
     * @param CommentRepositoryElastic $repositoryElastic
     * @param CommentDataBuilder $builder
     */
    public function __construct(CommentRepositoryElastic $repositoryElastic, CommentDataBuilder $builder)
    {
        $this->repositoryElastic = $repositoryElastic;
        $this->builder = $builder;
    }

    /**
     * @param GetAllChildrenCommentCommand $command
     * @return \App\Application\Query\Collection
     */
    public function __invoke(GetAllChildrenCommentCommand $command)
    {
        $data = $this->repositoryElastic->commentByCreatedAt($command->getPage(), $command->getLimit(), $command->getQuery());
        $total = $data->total;
        $data = $this->builder->build($data->data);

        return new Collection($command->getPage(),$command->getLimit(), $total, $data);
    }
}
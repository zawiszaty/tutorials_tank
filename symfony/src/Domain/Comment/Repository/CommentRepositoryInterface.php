<?php

namespace App\Domain\Comment\Repository;

use App\Domain\Comment\Comment;
use App\Domain\Common\ValueObject\AggregateRootId;

/**
 * Interface CommentRepositoryInterface.
 */
interface CommentRepositoryInterface
{
    /**
     * @param AggregateRootId $id
     *
     * @return Comment
     */
    public function get(AggregateRootId $id): Comment;

    /**
     * @param Comment $category
     */
    public function store(Comment $category): void;
}

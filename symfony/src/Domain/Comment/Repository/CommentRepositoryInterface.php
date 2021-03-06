<?php

namespace App\Domain\Comment\Repository;

use App\Domain\Comment\Comment;
use App\Domain\Common\ValueObject\AggregateRootId;

/**
 * Interface CommentRepositoryInterface.
 */
interface CommentRepositoryInterface
{
    public function get(AggregateRootId $id): Comment;

    public function store(Comment $category): void;
}

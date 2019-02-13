<?php

namespace App\Application\Query\Comment\GetAllChildrenComment;

/**
 * Class GetAllChildrenComment.
 */
class GetAllChildrenCommentCommand
{
    /**
     * @var int
     */
    private $page;

    /**
     * @var int
     */
    private $limit;

    /**
     * @var string
     */
    private $parrentComment;

    /**
     * GetAllCommand constructor.
     */
    public function __construct(int $page, int $limit, ?string $parrentComment = null)
    {
        $this->page = $page;
        $this->limit = $limit;
        $this->parrentComment = $parrentComment;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getParrentComment(): string
    {
        return $this->parrentComment;
    }
}

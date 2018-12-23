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
     *
     * @param int $page
     * @param int $limit
     * @param string|null $parrentComment
     */
    public function __construct(int $page, int $limit, ?string $parrentComment = null)
    {
        $this->page = $page;
        $this->limit = $limit;
        $this->parrentComment = $parrentComment;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return string
     */
    public function getParrentComment(): string
    {
        return $this->parrentComment;
    }
}

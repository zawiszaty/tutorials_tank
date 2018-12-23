<?php

namespace App\Application\Query\Comment\GetAllPostComment;

/**
 * Class GetAllPostCommentCommand.
 */
class GetAllPostCommentCommand
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
    private $post;

    /**
     * GetAllCommand constructor.
     *
     * @param int $page
     * @param int $limit
     * @param string|null $post
     */
    public function __construct(int $page, int $limit, ?string $post = null)
    {
        $this->page = $page;
        $this->limit = $limit;
        $this->post = $post;
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
     * @return null|array
     */
    public function getPost(): ?string
    {
        return $this->post;
    }
}

<?php

namespace App\Application\Command\Comment\Create;

/**
 * Class CreateCommentCommand.
 */
class CreateCommentCommand
{
    /**
     * @var string|null
     */
    private $content;

    /**
     * @var string|null
     */
    private $user;

    /**
     * @var string|null
     */
    private $parentComment;

    /**
     * @var string|null
     */
    private $post;

    /**
     * @return null|string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param null|string $content
     */
    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return null|string
     */
    public function getUser(): ?string
    {
        return $this->user;
    }

    /**
     * @param null|string $user
     */
    public function setUser(?string $user): void
    {
        $this->user = $user;
    }

    /**
     * @return null|string
     */
    public function getParentComment(): ?string
    {
        return $this->parentComment;
    }

    /**
     * @param null|string $parentComment
     */
    public function setParentComment(?string $parentComment): void
    {
        $this->parentComment = $parentComment;
    }

    /**
     * @return null|string
     */
    public function getPost(): ?string
    {
        return $this->post;
    }

    /**
     * @param null|string $post
     */
    public function setPost(?string $post): void
    {
        $this->post = $post;
    }
}

<?php

namespace App\Application\Command\Comment\Create;

/**
 * Class CreateCommentCommand.
 */
class CreateCommentCommand
{
    public $content;

    public $user;

    public $parentComment;

    public $post;

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content): void
    {
        $this->content = $content;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user): void
    {
        $this->user = $user;
    }

    public function getParentComment()
    {
        return $this->parentComment;
    }

    public function setParentComment($parentComment): void
    {
        $this->parentComment = $parentComment;
    }

    public function getPost()
    {
        return $this->post;
    }

    public function setPost($post): void
    {
        $this->post = $post;
    }
}

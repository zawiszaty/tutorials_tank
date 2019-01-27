<?php

namespace App\Application\Command\Post\Create;

use Symfony\Component\HttpFoundation\File\File;

/**
 * Class CreatePostCommand.
 */
class CreatePostCommand
{
    /**
     * @var string|null
     */
    public $id;

    /**
     * @var string|null
     */
    public $title;

    /**
     * @var string|null
     */
    public $content;

    /**
     * @var string|null
     */
    public $thumbnail;

    /**
     * @var string|null
     */
    public $type;

    /**
     * @var string|null
     */
    public $category;

    /**
     * @var string|null
     */
    public $user;

    /**
     * @var string|null
     */
    public $shortDescription;

    /**
     * @var File|null
     */
    public $file;
}

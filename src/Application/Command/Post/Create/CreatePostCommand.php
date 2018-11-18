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
    private $id;

    /**
     * @var string|null
     */
    private $title;

    /**
     * @var string|null
     */
    private $content;

    /**
     * @var string|null
     */
    private $thumbnail;

    /**
     * @var string|null
     */
    private $type;

    /**
     * @var string|null
     */
    private $category;

    /**
     * @var string|null
     */
    private $user;

    /**
     * @var string|null
     */
    private $shortDescription;

    /**
     * @return null|File
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @param null|File $file
     */
    public function setFile(?File $file): void
    {
        $this->file = $file;
    }

    /**
     * @var File|null
     */
    private $file;

    /**
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param null|string $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return null|string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param null|string $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

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
    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    /**
     * @param null|string $thumbnail
     */
    public function setThumbnail(?string $thumbnail): void
    {
        $this->thumbnail = $thumbnail;
    }

    /**
     * @return null|string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param null|string $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
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
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @param null|string $category
     */
    public function setCategory(?string $category): void
    {
        $this->category = $category;
    }

    /**
     * @return null|string
     */
    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    /**
     * @param null|string $shortDescription
     */
    public function setShortDescription(?string $shortDescription): void
    {
        $this->shortDescription = $shortDescription;
    }
}

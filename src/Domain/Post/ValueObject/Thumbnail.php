<?php

namespace App\Domain\Post\ValueObject;

/**
 * Class thumbnail
 * @package App\Domain\Post\ValueObject
 */
class Thumbnail
{
    /**
     * @var string|null
     */
    private $thumbnail;

    /**
     * thumbnail constructor.
     * @param string $thumbnail
     */
    public function __construct(?string $thumbnail)
    {
        $this->thumbnail = $thumbnail;
    }

    public static function fromString(?string $thumbnail)
    {
        return new self(
            $thumbnail
        );
    }

    public function toString()
    {
        return $this->thumbnail;
    }
}
<?php

namespace App\Domain\Post\ValueObject;

/**
 * Class Content.
 */
class Content
{
    /**
     * @var string
     */
    private $content;

    /**
     * content constructor.
     *
     * @param string $content
     */
    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public static function fromString(string $content)
    {
        return new self(
            $content
        );
    }

    public function toString()
    {
        return $this->content;
    }
}

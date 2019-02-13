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
     */
    public function __construct(string $content)
    {
        $this->content = $content;
    }

    /**
     * @return Content
     */
    public static function fromString(string $content)
    {
        return new self(
            $content
        );
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->content;
    }
}

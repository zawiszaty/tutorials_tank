<?php

namespace App\Domain\Post\ValueObject;

/**
 * Class Title.
 */
class Title
{
    /**
     * @var string
     */
    private $title;

    /**
     * Title constructor.
     */
    public function __construct(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return Title
     */
    public static function fromString(string $title)
    {
        return new self(
            $title
        );
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->title;
    }
}

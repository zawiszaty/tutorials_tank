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
     *
     * @param string $title
     */
    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public static function fromString(string $title)
    {
        return new self(
            $title
        );
    }

    public function toString()
    {
        return $this->title;
    }
}

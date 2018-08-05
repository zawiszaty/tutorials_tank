<?php

namespace App\Application\Query;

/**
 * Class Collection
 * @package App\Application\Query
 */
class Collection
{
    /**
     * @var array
     */
    public $data;

    /**
     * Collection constructor.
     * @param array $data
     * @param int $count
     */
    public function __construct(array $data)
    {
        return $data;
    }
}
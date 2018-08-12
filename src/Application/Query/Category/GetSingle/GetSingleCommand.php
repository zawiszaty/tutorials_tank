<?php

namespace App\Application\Query\Category\GetSingle;

use App\Domain\Common\ValueObject\AggregateRootId;

/**
 * Class GetSingleCommand
 * @package App\Application\Query\Category\GetSingle
 */
class GetSingleCommand
{
    /**
     * @var AggregateRootId
     */
    private $id;

    /**
     * GetSingleCommand constructor.
     * @param AggregateRootId $id
     */
    public function __construct(AggregateRootId $id)
    {
        $this->id = $id;
    }

    /**
     * @return AggregateRootId
     */
    public function getId(): AggregateRootId
    {
        return $this->id;
    }
}
<?php

namespace App\Application\Query\Post\GetSingle;

use App\Domain\Common\ValueObject\AggregateRootId;

/**
 * Class GetSingleCommand.
 */
class GetSingleCommand
{
    /**
     * @var AggregateRootId
     */
    private $id;

    /**
     * GetSingleCommand constructor.
     *
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

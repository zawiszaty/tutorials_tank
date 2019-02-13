<?php

namespace App\Application\Query\Category\GetSingle;

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
     */
    public function __construct(AggregateRootId $id)
    {
        $this->id = $id;
    }

    public function getId(): AggregateRootId
    {
        return $this->id;
    }
}

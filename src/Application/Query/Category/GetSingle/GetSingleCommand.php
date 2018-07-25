<?php

namespace App\Application\Query\Category\GetSingle;


use App\Domain\Common\ValueObject\AggregatRootId;

class GetSingleCommand
{
    /**
     * @var AggregatRootId
     */
    private $id;

    public function __construct(AggregatRootId $id)
    {
        $this->id = $id;
    }

    /**
     * @return AggregatRootId
     */
    public function getId(): AggregatRootId
    {
        return $this->id;
    }
}
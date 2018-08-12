<?php

namespace App\Domain\Common\Event;

use App\Domain\Common\ValueObject\AggregatRootId;
use Broadway\Serializer\Serializable;

/**
 * Class AbstractEvent
 * @package App\Domain\Common\Event
 */
abstract class AbstractEvent implements Serializable
{
    /**
     * @var AggregatRootId
     */
    protected $id;

    /**
     * @return AggregatRootId
     */
    public function getId(): AggregatRootId
    {
        return $this->id;
    }
}
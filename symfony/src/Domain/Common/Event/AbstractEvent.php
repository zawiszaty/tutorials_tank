<?php

namespace App\Domain\Common\Event;

use App\Domain\Common\ValueObject\AggregateRootId;
use Broadway\Serializer\Serializable;

/**
 * Class AbstractEvent.
 */
abstract class AbstractEvent implements Serializable
{
    /**
     * @var AggregateRootId
     */
    protected $id;

    public function getId(): AggregateRootId
    {
        return $this->id;
    }
}

<?php

namespace App\Infrastructure\Share\Event\Query;

use App\Infrastructure\Share\Query\Repository\ElasticRepository;
use Broadway\Domain\DomainMessage;

/**
 * Class EventElasticRepository
 *
 * @package App\Infrastructure\Share\Event\Query
 */
final class EventElasticRepository extends ElasticRepository
{
    private const INDEX = 'events';

    /**
     * @param DomainMessage $message
     */
    public function storeEvent(DomainMessage $message): void
    {
        $document = [
            'type'        => $message->getType(),
            'payload'     => $message->getPayload()->serialize(),
            'occurred_on' => $message->getRecordedOn()->toString(),
        ];
        $this->add($document);
    }

    /**
     * EventElasticRepository constructor.
     *
     * @param array $elasticConfig
     */
    public function __construct(array $elasticConfig)
    {
        parent::__construct($elasticConfig, self::INDEX);
    }
}

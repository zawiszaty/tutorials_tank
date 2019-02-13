<?php

namespace App\Infrastructure\Share\Event\Query;

use App\Infrastructure\Share\Query\Repository\ElasticRepository;
use Broadway\Domain\DomainMessage;

/**
 * Class EventElasticRepository.
 */
final class EventElasticRepository extends ElasticRepository
{
    private const INDEX = 'events';

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
     */
    public function __construct(array $elasticConfig)
    {
        parent::__construct($elasticConfig, self::INDEX);
    }
}

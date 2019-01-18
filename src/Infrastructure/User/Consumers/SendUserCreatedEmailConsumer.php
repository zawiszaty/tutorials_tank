<?php

namespace App\Infrastructure\User\Consumers;

use App\Application\Command\User\SendEmail\SendEmailCommand;
use App\Domain\User\Event\UserWasCreated;
use Broadway\Domain\DomainMessage;
use League\Tactician\CommandBus;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class SendUserCreatedEmailConsumer.
 */
class SendUserCreatedEmailConsumer implements ConsumerInterface
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @param AMQPMessage $msg
     *
     * @return mixed|void
     *
     * @throws \Assert\AssertionFailedException
     */
    public function execute(AMQPMessage $msg)
    {
        /** @var DomainMessage $domainMessage */
        $domainMessage = unserialize($msg->body);
        /** @var UserWasCreated $event */
        $event = $domainMessage->getPayload();
        $sendEmailCommand = new SendEmailCommand($event->getEmail(), $event->getConfirmationToken()->toString());
        $this->commandBus->handle($sendEmailCommand);
    }

    /**
     * SendUserCreatedEmailConsumer constructor.
     *
     * @param CommandBus $commandBus
     */
    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }
}

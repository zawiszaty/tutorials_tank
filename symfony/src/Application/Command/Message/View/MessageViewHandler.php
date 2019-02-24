<?php

namespace App\Application\Command\Message\View;

use App\Application\Command\CommandHandlerInterface;
use App\Infrastructure\Message\MessageView;
use App\Infrastructure\Message\Query\MessageRepositoryElastic;
use App\Infrastructure\User\Query\Projections\UserView;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class MessageViewHandler implements CommandHandlerInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var MessageRepositoryElastic
     */
    private $messageRepositoryElastic;

    public function __construct(EntityManagerInterface $entityManager, MessageRepositoryElastic $messageRepositoryElastic)
    {
        $this->entityManager = $entityManager;
        $this->messageRepositoryElastic = $messageRepositoryElastic;
    }

    /**
     * @param MessageViewCommand $command
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function __invoke(MessageViewCommand $command): void
    {
        $repository = $this->entityManager->getRepository(MessageView::class);

        foreach ($command->getMessages() as $message) {
            $message = $repository->find($message);
            $message->setDisplayed();
            $this->messageRepositoryElastic->edit($message->serialize());
        }
        $this->entityManager->flush();
    }
}
<?php

namespace App\Application\Command\Comment\Delete;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Comment\Repository\CommentRepository;
use App\Infrastructure\User\Query\Projections\UserView;
use App\Infrastructure\User\Query\Repository\MysqlUserReadModelRepository;
use Symfony\Component\Finder\Exception\AccessDeniedException;

/**
 * Class DeleteCommentHandler.
 */
class DeleteCommentHandler implements CommandHandlerInterface
{
    /**
     * @var CommentRepository
     */
    private $commentRepository;
    /**
     * @var MysqlUserReadModelRepository
     */
    private $mysqlUserReadModelRepository;

    /**
     * DeleteCommentHandler constructor.
     */
    public function __construct(
        CommentRepository $commentRepository,
        MysqlUserReadModelRepository $mysqlUserReadModelRepository
    ) {
        $this->commentRepository = $commentRepository;
        $this->mysqlUserReadModelRepository = $mysqlUserReadModelRepository;
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    public function __invoke(DeleteCommentCommand $deleteCommentCommand)
    {
        $aggregateRoot = $this->commentRepository->get(AggregateRootId::fromString($deleteCommentCommand->getId()));
        /** @var UserView $user */
        $user = $this->mysqlUserReadModelRepository->get(UserView::class, $deleteCommentCommand->getUser());
        if ($aggregateRoot->getUser() !== $deleteCommentCommand->getUser() && !\in_array('ROLE_ADMIN', $user->getRoles())) {
            throw new AccessDeniedException();
        }
        $aggregateRoot->delete($deleteCommentCommand->getUser());
        $this->commentRepository->store($aggregateRoot);
    }
}

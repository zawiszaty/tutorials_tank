<?php

namespace App\Application\Command\Post\Delete;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Post\Repository\PostRepository;
use App\Infrastructure\User\Query\Projections\UserView;
use App\Infrastructure\User\Query\Repository\MysqlUserReadModelRepository;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class DeletePostHandler.
 */
class DeletePostHandler implements CommandHandlerInterface
{
    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var MysqlUserReadModelRepository
     */
    private $mysqlUserReadModelRepository;

    /**
     * DeletePostHandler constructor.
     */
    public function __construct(PostRepository $postRepository, MysqlUserReadModelRepository $mysqlUserReadModelRepository)
    {
        $this->postRepository = $postRepository;
        $this->mysqlUserReadModelRepository = $mysqlUserReadModelRepository;
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    public function __invoke(DeletePostCommand $deletePostCommand)
    {
        $aggregateRoot = $this->postRepository->get(AggregateRootId::fromString($deletePostCommand->getId()));
        /** @var UserView $user */
        $user = $this->mysqlUserReadModelRepository->get(UserView::class, $deletePostCommand->getUser());
        if ($aggregateRoot->getUser() !== $deletePostCommand->getUser() && !\in_array('ROLE_ADMIN', $user->getRoles())) {
            throw new AccessDeniedException();
        }
        $aggregateRoot->delete($deletePostCommand->getUser());
        $this->postRepository->store($aggregateRoot);
    }
}

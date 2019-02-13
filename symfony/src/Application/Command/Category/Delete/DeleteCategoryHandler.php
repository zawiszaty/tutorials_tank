<?php

namespace App\Application\Command\Category\Delete;

use App\Application\Command\CommandHandlerInterface;
use App\Application\Command\Post\Edit\EditPostCommand;
use App\Domain\Category\Repository\CategoryRepositoryInterface;
use App\Domain\Post\Exception\CreatePostException;
use App\Infrastructure\Post\Query\Repository\PostRepositoryElastic;
use League\Tactician\CommandBus;

/**
 * Class DeleteCategoryHandler.
 */
class DeleteCategoryHandler implements CommandHandlerInterface
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var PostRepositoryElastic
     */
    private $postRepositoryElastic;

    /**
     * DeleteCategoryHandler constructor.
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository, CommandBus $commandBus, PostRepositoryElastic $postRepositoryElastic)
    {
        $this->categoryRepository = $categoryRepository;
        $this->commandBus = $commandBus;
        $this->postRepositoryElastic = $postRepositoryElastic;
    }

    public function __invoke(DeleteCategoryCommand $categoryCommand): void
    {
        $posts = $this->postRepositoryElastic->search([
            'query' => [
                'match' => [
                    'category' => $categoryCommand->getId()->toString(),
                ],
            ],
        ]);

        foreach ($posts['hits']['hits'] as $post) {
            $post = $post['_source'];
            $editPostCommand = new EditPostCommand();
            $editPostCommand->setUser($post['user']);
            $editPostCommand->setCategory(null);
            $editPostCommand->setContent($post['content']);
            $editPostCommand->setId($post['id']);
            $editPostCommand->setThumbnail($post['thumbnail']);
            $editPostCommand->setShortDescription($post['shortDescription']);
            $editPostCommand->setType($post['type']);
            $editPostCommand->setTitle($post['title']);

            try {
                $this->commandBus->handle($editPostCommand);
            } catch (CreatePostException $exception) {
            }
        }
        $category = $this->categoryRepository->get($categoryCommand->getId());
        $category->delete();
        $this->categoryRepository->store($category);
    }
}

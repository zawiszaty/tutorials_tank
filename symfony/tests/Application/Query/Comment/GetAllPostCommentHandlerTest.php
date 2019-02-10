<?php

declare(strict_types=1);

namespace App\Tests\Application\Query\Comment;

use App\Application\Command\Category\Create\CreateCategoryCommand;
use App\Application\Command\Comment\Create\CreateCommentCommand;
use App\Application\Command\Post\Create\CreatePostCommand;
use App\Application\Command\User\Create\CreateUserCommand;
use App\Application\Query\Comment\GetAllPostComment\GetAllPostCommentCommand;
use App\Domain\Category\Event\CategoryWasCreated;
use App\Domain\Post\Event\CreatePostEvent;
use App\Domain\User\Event\UserWasCreated;
use App\Tests\Application\ApplicationTestCase;
use App\Tests\Infrastructure\Share\Event\EventCollectorListener;
use Broadway\Domain\DomainMessage;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\File;

class GetAllPostCommentHandlerTest extends ApplicationTestCase
{
    /**
     * @test
     *
     * @group integration
     *
     * @throws \Exception
     */
    public function command_handler_must_fire_domain_event(): void
    {
        $name = 'test';
        $post = $this->createPost();
        $command = new CreateCommentCommand();
        $command->content = 'test';
        $command->user = $this->createUser();
        $command->post = $post;
        $command->parentComment = null;
        $this
            ->handle($command);
        $this
            ->handle($command);
        $query = new GetAllPostCommentCommand(
            1,
            10,
            $post->getId()
        );
//        $result = $this->ask($query);
//        self::assertNotEmpty($result);
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Domain\Comment;

use App\Domain\Comment\Comment;
use App\Domain\Comment\Event\CommentWasCreated;
use App\Domain\Comment\Event\CommentWasDeletedEvent;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Post\ValueObject\Content;
use Broadway\Domain\DomainMessage;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CommentTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function given_a_valid_credential_parrent_comment_null_it_should_create_a_comment_instance(): void
    {
        $content = 'test';
        $comment = Comment::create(
            AggregateRootId::fromString(Uuid::uuid4()->toString()),
            Content::fromString($content),
            null,
            '1',
            '1'
        );
        self::assertSame($content, $comment->getContent()->toString());
        self::assertNotNull($comment->getId());
        $events = $comment->getUncommittedEvents();
        self::assertCount(1, $events->getIterator(), 'Only one event should be in the buffer');
        /** @var DomainMessage $event */
        $event = $events->getIterator()->current();
        self::assertInstanceOf(CommentWasCreated::class, $event->getPayload(), 'First event should be CommentWasCreated');
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function given_a_valid_credential_it_should_create_a_comment_instance(): void
    {
        $content = 'test';
        $comment = Comment::create(
            AggregateRootId::fromString(Uuid::uuid4()->toString()),
            Content::fromString($content),
            '1',
            '1',
            '1'
        );
        self::assertSame($content, $comment->getContent()->toString());
        self::assertNotNull($comment->getId());
        $events = $comment->getUncommittedEvents();
        self::assertCount(1, $events->getIterator(), 'Only one event should be in the buffer');
        /** @var DomainMessage $event */
        $event = $events->getIterator()->current();
        self::assertInstanceOf(CommentWasCreated::class, $event->getPayload(), 'First event should be CommentWasCreated');
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function given_a_delete_comment_instance(): void
    {
        $content = 'test';
        $comment = Comment::create(
            AggregateRootId::fromString(Uuid::uuid4()->toString()),
            Content::fromString($content),
            null,
            '1',
            '1'
        );

        $comment->delete('1');
        $events = $comment->getUncommittedEvents();
        self::assertCount(2, $events->getIterator(), 'Only one event should be in the buffer');
        /** @var DomainMessage $event */
        $event = $events->getIterator()->offsetGet(1);
        self::assertInstanceOf(CommentWasDeletedEvent::class, $event->getPayload(), 'First event should be CommentWasCreated');
    }
}

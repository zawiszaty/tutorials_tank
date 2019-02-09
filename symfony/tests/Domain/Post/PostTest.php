<?php

declare(strict_types=1);

namespace App\Tests\Domain\Post;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Post\Event\CreatePostEvent;
use App\Domain\Post\Event\PostEventDelete;
use App\Domain\Post\Event\PostWasEdited;
use App\Domain\Post\Post;
use App\Domain\Post\ValueObject\Content;
use App\Domain\Post\ValueObject\Thumbnail;
use App\Domain\Post\ValueObject\Title;
use Broadway\Domain\DomainMessage;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class PostTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function given_a_valid_credential_it_should_create_a_post_instance(): void
    {
        $content = 'test';
        $post = Post::create(
            AggregateRootId::fromString(Uuid::uuid4()->toString()),
            Title::fromString('title'),
            Content::fromString($content),
            Thumbnail::fromString('test'),
            'oder_site',
            '1',
            '1',
            'test'
        );
        self::assertSame($content, $post->getContent()->toString());
        self::assertSame('title', $post->getTitle()->toString());
        self::assertNotNull($post->getId());
        $events = $post->getUncommittedEvents();
        self::assertCount(1, $events->getIterator(), 'Only one event should be in the buffer');
        /** @var DomainMessage $event */
        $event = $events->getIterator()->current();
        self::assertInstanceOf(CreatePostEvent::class, $event->getPayload(), 'First event should be CreatePostEvent');
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function given_a_valid_credential_it_should_edit_a_post_instance(): void
    {
        $content = 'test';
        $post = Post::create(
            AggregateRootId::fromString(Uuid::uuid4()->toString()),
            Title::fromString('title'),
            Content::fromString($content),
            Thumbnail::fromString('test'),
            'oder_site',
            '1',
            '1',
            'test'
        );
        $post->edit(
            Title::fromString('title2'),
            Content::fromString('test2'),
            Thumbnail::fromString('test2'),
            'oder_site',
            'test2',
            'test2',
            'test2'
        );
        $events = $post->getUncommittedEvents();
        /** @var DomainMessage $event */
        $event = $events->getIterator()->offsetGet(1);
        self::assertSame($post->getTitle()->toString(), 'title2');
        self::assertSame($post->getContent()->toString(), 'test2');
        self::assertSame($post->getThumbnail()->toString(), 'test2');
        self::assertSame($post->getUser(), 'test2');
        self::assertSame($post->getCategory(), 'test2');
        self::assertSame($post->getShortDescription(), 'test2');
        self::assertInstanceOf(PostWasEdited::class, $event->getPayload(), 'First event should be CreatePostEvent');
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function given_delete_post_instance(): void
    {
        $content = 'test';
        $post = Post::create(
            AggregateRootId::fromString(Uuid::uuid4()->toString()),
            Title::fromString('title'),
            Content::fromString($content),
            Thumbnail::fromString('test'),
            'oder_site',
            '1',
            '1',
            'test'
        );
        $post->delete('1');
        $events = $post->getUncommittedEvents();
        /** @var DomainMessage $event */
        $event = $events->getIterator()->offsetGet(1);
        self::assertInstanceOf(PostEventDelete::class, $event->getPayload(), 'First event should be PostEventDelete');
    }
}

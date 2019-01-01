<?php

declare(strict_types=1);

namespace App\Tests\Domain\Category;

use App\Domain\Category\Category;
use App\Domain\Category\Event\CategoryWasCreated;
use App\Domain\Category\Event\CategoryWasDeleted;
use App\Domain\Category\Event\NameWasChanged;
use App\Domain\Category\ValueObject\Name;
use App\Domain\Common\ValueObject\AggregateRootId;
use Broadway\Domain\DomainMessage;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CategoryTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function given_a_valid_name_it_should_create_a_category_instance(): void
    {
        $name = 'test';
        $category = Category::create(
          AggregateRootId::fromString(Uuid::uuid4()->toString()),
            Name::fromString($name)
        );
        self::assertSame($name, $category->getName());
        self::assertNotNull($category->getId());
        $events = $category->getUncommittedEvents();
        self::assertCount(1, $events->getIterator(), 'Only one event should be in the buffer');
        /** @var DomainMessage $event */
        $event = $events->getIterator()->current();
        self::assertInstanceOf(CategoryWasCreated::class, $event->getPayload(), 'First event should be CategoryWasCreated');
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function given_a_valid_name_it_should_change_a_category_instance(): void
    {
        $name = 'test';
        $category = Category::create(
            AggregateRootId::fromString(Uuid::uuid4()->toString()),
            Name::fromString($name)
        );
        self::assertSame($name, $category->getName());
        self::assertNotNull($category->getId());
        $category->changeName(Name::fromString('test2'));
        self::assertSame('test2', $category->getName());
        $events = $category->getUncommittedEvents();
        self::assertCount(2, $events->getIterator(), 'Only one event should be in the buffer');
        /** @var DomainMessage $event */
        $event = $events->getIterator()->offsetGet(1);
        self::assertInstanceOf(NameWasChanged::class, $event->getPayload(), 'First event should be NameWasChanged');
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function given_category_was_delete(): void
    {
        $name = 'test';
        $category = Category::create(
            AggregateRootId::fromString(Uuid::uuid4()->toString()),
            Name::fromString($name)
        );
        self::assertSame($name, $category->getName());
        self::assertNotNull($category->getId());
        $category->delete();
        $events = $category->getUncommittedEvents();
        self::assertCount(2, $events->getIterator(), 'Only one event should be in the buffer');
        /** @var DomainMessage $event */
        $event = $events->getIterator()->offsetGet(1);
        self::assertInstanceOf(CategoryWasDeleted::class, $event->getPayload(), 'First event should be CategoryWasDeleted');
    }
}

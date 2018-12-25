<?php

declare(strict_types=1);

namespace App\UI\CLI\Command;

use App\Application\Command\Events\Replay\ReplayEventsCommand;
use App\Infrastructure\Share\Event\IterableAggregateEventStoreInterface;
use League\Tactician\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EventStoreReplayCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('event-store:replay')
            ->setDescription('It will replay events in the event store.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = new ReplayEventsCommand();
        $output->writeln('<info>Events replayed.</info>');
        $this->commandBus->handle($command);
    }

    public function __construct(
        CommandBus $commandBus,
        IterableAggregateEventStoreInterface $iterableDbalEventStore
    ) {
        parent::__construct();
        $this->commandBus = $commandBus;
        $this->iterableDbalEventStore = $iterableDbalEventStore;
    }

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var IterableAggregateEventStoreInterface
     */
    private $iterableDbalEventStore;
}

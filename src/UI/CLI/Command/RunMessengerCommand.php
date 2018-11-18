<?php

namespace App\UI\CLI\Command;

use App\Infrastructure\Message\Message;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RunMessengerCommand
 * @package App\UI\CLI\Command
 */
class RunMessengerCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('create:message-server')
            ->setDescription('Creates a message server.')
            ->setHelp('This command allows you to create a message server');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $server = IoServer::factory(new HttpServer(
            new WsServer(
                new Message($this->getContainer())
            )
        ), 8123);

        $server->run();
    }
}
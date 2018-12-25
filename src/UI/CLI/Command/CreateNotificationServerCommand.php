<?php

namespace App\UI\CLI\Command;

use App\Infrastructure\Notification\Notification;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\Wamp\WampServer;
use React\EventLoop\Factory;
use React\Socket\Server;
use React\ZMQ\Context;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateNotificationServerCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'app:create-notification-server';

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
        $loop = Factory::create();
        $pusher = new Notification($this->getContainer());
        // Listen for the web server to make a ZeroMQ push after an ajax request
        $context = new Context($loop);
        $pull = $context->getSocket(\ZMQ::SOCKET_PULL);
        $pull->bind('tcp://127.0.0.1:5555'); // Binding to 127.0.0.1 means the only client that can connect is itself
        $pull->on('message', array($pusher, 'onNotify'));

        // Set up our WebSocket server for clients wanting real-time updates
        $webSock = new Server('0.0.0.0:8888', $loop); // Binding to 0.0.0.0 means remotes can connect
        $webServer = new  IoServer(
            new HttpServer(
                new \Ratchet\WebSocket\WsServer(
                    new WampServer(
                        $pusher
                    )
                )
            ),
            $webSock
        );
        $loop->run();
    }
}

<?php

namespace App\UI\CLI\Command;

use App\Application\Command\User\Create\CreateUserCommand;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateAdminCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'app:create-admin';

    protected function configure()
    {
        $this
            ->setDescription('This command create admin from command line')
            ->addArgument('username', InputArgument::REQUIRED, 'Username')
            ->addArgument('password', InputArgument::REQUIRED, 'Password')
            ->addArgument('email', InputArgument::REQUIRED, 'Email')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');
        $email = $input->getArgument('email');
        $command = new CreateUserCommand();
        $command->username = $username;
        $command->plainPassword = $password;
        $command->email = $email;
        $command->roles = [
            'ROLE_USER',
            'ROLE_ADMIN',
        ];
        $command->banned = false;
        $commandBus = $this->getContainer()->get('tactician.commandbus.command');
        $commandBus->handle($command);
        $io = new SymfonyStyle($input, $output);
        $io->note('Admin Was Created');
    }
}

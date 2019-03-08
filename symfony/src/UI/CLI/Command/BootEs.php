<?php

namespace App\UI\CLI\Command;

use App\Infrastructure\Category\Repository\CategoryRepositoryElastic;
use App\Infrastructure\Comment\Query\CommentRepositoryElastic;
use App\Infrastructure\Message\Query\MessageRepositoryElastic;
use App\Infrastructure\Notification\Query\NotificationRepositoryElastic;
use App\Infrastructure\Post\Query\Repository\PostRepositoryElastic;
use App\Infrastructure\User\Repository\UserRepositoryElastic;
use Elasticsearch\ClientBuilder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class CreateAdminCommand.
 */
class BootEs extends ContainerAwareCommand
{
    protected static $defaultName = 'app:boot';

    protected function configure()
    {
        $this
            ->setDescription('boot es');
    }

    /**
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = ClientBuilder::fromConfig(['hosts' => ['elasticsearch:9200']], true);
        $usersEs = $this->getContainer()->get(UserRepositoryElastic::class);
        $usersEs->boot();
        $postes = $this->getContainer()->get(PostRepositoryElastic::class);
        $postes->boot();
        $postes = $this->getContainer()->get(CategoryRepositoryElastic::class);
        $postes->boot();
        $postes = $this->getContainer()->get(CommentRepositoryElastic::class);
        $postes->boot();
        $postes = $this->getContainer()->get(NotificationRepositoryElastic::class);
        $postes->boot();
        $postes = $this->getContainer()->get(MessageRepositoryElastic::class);
        $postes->boot();
        $io = new SymfonyStyle($input, $output);
        $io->note('Data ');
    }
}

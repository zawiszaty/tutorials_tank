<?php

namespace App\UI\CLI\Command;

use App\Infrastructure\Category\Query\Projections\CategoryView;
use App\Infrastructure\Category\Repository\CategoryRepositoryElastic;
use App\Infrastructure\Comment\Query\CommentRepositoryElastic;
use App\Infrastructure\Comment\Query\Projections\CommentView;
use App\Infrastructure\Post\Query\Projections\PostView;
use App\Infrastructure\Post\Query\Repository\PostRepositoryElastic;
use App\Infrastructure\User\Query\Projections\UserView;
use App\Infrastructure\User\Repository\UserRepositoryElastic;
use Doctrine\ORM\EntityManager;
use Elasticsearch\ClientBuilder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class CreateAdminCommand.
 */
class DateToEs extends ContainerAwareCommand
{
    protected static $defaultName = 'app:es';

    protected function configure()
    {
        $this
            ->setDescription('This command create admin from command line');
    }

    /**
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = ClientBuilder::fromConfig(['hosts' => ['elasticsearch:9200']], true);
        $params = ['index' => '*'];
        $response = $client->indices()->delete($params);
        /** @var EntityManager $manager */
        $manager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $users = $manager->getRepository(UserView::class)->findAll();
        $usersEs = $this->getContainer()->get(UserRepositoryElastic::class);
        /** @var UserView $user */
        foreach ($users as $user) {
            $usersEs->store($user->serializeProjections());
        }
        $posts = $manager->getRepository(PostView::class)->findAll();
        $postes = $this->getContainer()->get(PostRepositoryElastic::class);
        /** @var PostView $post */
        foreach ($posts as $post) {
            $postes->store($post->serialize());
        }
        $posts = $manager->getRepository(CategoryView::class)->findAll();
        $postes = $this->getContainer()->get(CategoryRepositoryElastic::class);
        /** @var CategoryView $post */
        foreach ($posts as $post) {
            $postes->store($post->serialize());
        }
        $posts = $manager->getRepository(CommentView::class)->findAll();
        $postes = $this->getContainer()->get(CommentRepositoryElastic::class);
        /** @var CommentView $post */
        foreach ($posts as $post) {
            $postes->store($post->serialize());
        }
        $io = new SymfonyStyle($input, $output);
        $io->note('Data ');
    }
}

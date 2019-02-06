<?php

declare(strict_types=1);

namespace App\Infrastructure\Share\Migrations;

use Broadway\EventStore\Dbal\DBALEventStore;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180719122713 extends AbstractMigration implements ContainerAwareInterface
{
    /**
     * @param Schema $schema
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function up(Schema $schema)
    {
        $this->eventStore->configureSchema($schema);
        $this->em->flush();
    }

    /**
     * @param Schema $schema
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('api.events');
        $this->em->flush();
    }

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->eventStore = $container->get(DBALEventStore::class);
        $this->em = $container->get('doctrine.orm.entity_manager');
    }

    /** @var EntityManager */
    private $em;

    /** @var DBALEventStore */
    private $eventStore;
}

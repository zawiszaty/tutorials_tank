<?php

use Behat\Behat\Context\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * This context class contains the definitions of the steps used by the demo
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 *
 * @see http://behat.org/en/latest/quick_start.html
 */
class FeatureContext implements Context
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private static $container;

    /**
     * @var Response|null
     */
    private $response;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        self::$container = $this->kernel->getContainer();
    }

    /**
     * @When a demo scenario sends a request to :path
     */
    public function aDemoScenarioSendsARequestTo(string $path)
    {
        $this->response = $this->kernel->handle(Request::create($path, 'GET'));
    }

    /**
     * @Then the response should be received
     */
    public function theResponseShouldBeReceived()
    {
        if ($this->response === null) {
            throw new \RuntimeException('No response received');
        }
    }

    /**
     * @BeforeScenario
     */
    public function cleanDatabase()
    {
        $connection = self::$container->get('doctrine')->getConnection();
        $connection->beginTransaction();
        $connection->query('SET FOREIGN_KEY_CHECKS=0');
        $connection->query('DELETE FROM events');
        $connection->query('DELETE FROM category');
        $connection->query('SET FOREIGN_KEY_CHECKS=1');
        $connection->commit();
    }

    /**
     * @When The Category was be changed
     */
    public function theCategoryWasBeChanged()
    {
        $entityManager = self::$container->get('doctrine')->getManager();
        $query = $entityManager->createQuery(
            'SELECT p
             FROM App\Infrastructure\Category\Query\Projections\CategoryView p
             WHERE p.id = :price
             '
        )->setParameter('price', 'f24c3526-8d75-11e8-9eb6-529269fb1459');
        $category = $query->execute();
        $serializer = JMS\Serializer\SerializerBuilder::create()->build();
        $jsonContent = json_decode($serializer->serialize($category, 'json'), true);
        if ($jsonContent[0]['name'] != 'King2') {
            throw new Exception();
        }
    }

    /**
     * @When The Category was be deleted
     */
    public function theCategoryWasBeDeleted()
    {
        $entityManager = self::$container->get('doctrine')->getManager();
        $query = $entityManager->createQuery(
            'SELECT p
             FROM App\Infrastructure\Category\Query\Projections\CategoryView p
             WHERE p.id = :price
             '
        )->setParameter('price', 'f24c3526-8d75-11e8-9eb6-529269fb1459');
        $category = $query->execute();
        $serializer = JMS\Serializer\SerializerBuilder::create()->build();
        $jsonContent = json_decode($serializer->serialize($category, 'json'), true);
        if ($jsonContent[0]['deleted'] != '1') {
            throw new Exception();
        }
    }

    /**
     * @When I have Category in database
     */
    public function iHaveCategoryInDatabase()
    {
        $connection = self::$container->get('doctrine')->getConnection();
        $connection->beginTransaction();
        $connection->query('INSERT INTO `category` (`id`, `name`, `deleted`) VALUES (\'d55874ef-e2b2-4d03-a164-54bbfbdd9599\', \'king\', \'0\');');
        $connection->query('INSERT INTO `events` (`id`, `uuid`, `playhead`, `payload`, `metadata`, `recorded_on`, `type`) VALUES (NULL, 0xd55874efe2b24d03a16454bbfbdd9599, \'0\', \'{\"class\":\"App\\\\Domain\\\\Category\\\\Event\\\\CategoryWasCreated\",\"payload\":{\"id\":\"d55874ef-e2b2-4d03-a164-54bbfbdd9599\",\"name\":\"King\",\"deleted\":false}}\', \'{\"class\":\"Broadway\\\\Domain\\\\Metadata\",\"payload\":[]}\', \'2018-07-23T09:13:47.196876+00:00\', \'App.Domain.Category.Event.CategoryWasCreated\');');
        $connection->commit();
    }
}

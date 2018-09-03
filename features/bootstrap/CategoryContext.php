<?php

use Behat\Behat\Context\Context;
use Symfony\Component\HttpKernel\KernelInterface;

class CategoryContext implements Context
{
    protected $request;

    protected $id;

    protected $kernel;

    protected static $container;

    /**
     * FeatureContext constructor.
     *
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        self::$container = $this->kernel->getContainer();
    }

    /**
     * @When I add Category to databse
     */
    public function iAddCategoryToDatabse()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->post('nginx/category', [
            GuzzleHttp\RequestOptions::JSON => ['name' => 'King'],
        ]);
        $this->id = json_decode($response->getBody(), true)['id'];
    }

    /**
     * @When I send edit request
     */
    public function iSendEditRequest()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->patch('nginx/category/' . $this->id, [
            GuzzleHttp\RequestOptions::JSON => ['name' => 'King2'],
        ]);
        if (\Symfony\Component\HttpFoundation\Response::HTTP_OK != $response->getStatusCode()) {
            throw new \Behat\Behat\Tester\Exception\PendingException();
        }
    }

    /**
     * @Then The Category was be updated
     */
    public function theCategoryWasBeUpdated()
    {
        $entityManager = self::$container->get('doctrine')->getManager();
        $query = $entityManager->createQuery(
            'SELECT p
             FROM App\Infrastructure\Category\Query\Projections\CategoryView p
             WHERE p.id = :price
             '
        )->setParameter('price', $this->id);
        $category = $query->execute();
        $serializer = JMS\Serializer\SerializerBuilder::create()->build();
        $jsonContent = json_decode($serializer->serialize($category, 'json'), true);
        if ($jsonContent[0]['name'] != 'King2') {
            throw new Exception();
        }
    }

    /**
     * @When I send delete request
     */
    public function iSendDeleteRequest()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->delete('nginx/category/' . $this->id);
        if (\Symfony\Component\HttpFoundation\Response::HTTP_ACCEPTED != $response->getStatusCode()) {
            throw new \Behat\Behat\Tester\Exception\PendingException();
        }
    }

    /**
     * @Then The Category was be deleted
     */
    public function theCategoryWasBeDeleted()
    {
        $entityManager = self::$container->get('doctrine')->getManager();
        $query = $entityManager->createQuery(
            'SELECT p
             FROM App\Infrastructure\Category\Query\Projections\CategoryView p
             WHERE p.id = :price
             '
        )->setParameter('price', $this->id);
        $category = $query->execute();
        $serializer = JMS\Serializer\SerializerBuilder::create()->build();
        $jsonContent = json_decode($serializer->serialize($category, 'json'), true);
        if ($jsonContent) {
            throw new Exception();
        }
    }
}

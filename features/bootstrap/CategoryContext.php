<?php

use Behat\Behat\Context\Context;
use Symfony\Component\HttpKernel\KernelInterface;
use Behatch\HttpCall\Request;

class CategoryContext implements Context
{
    protected $request;

    protected $id;

    protected $kernel;

    protected static $container;

    protected $client;

    /**
     * FeatureContext constructor.
     *
     * @param KernelInterface $kernel
     * @param Request         $request
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        $this->client = \Elasticsearch\ClientBuilder::fromConfig(['hosts' => ['elasticsearch:9200']], true);
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
        $params = [
            'index' => 'category',
            'type'  => 'category',
            'id'    => $this->id,
        ];
        $data = $this->client->get($params);
        if ('King2' != $data['_source']['name']) {
            throw new \Behat\Behat\Tester\Exception\PendingException();
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
        $params = [
            'index' => 'category',
            'type'  => 'category',
            'id'    => $this->id,
        ];

        try {
            $this->client->get($params);
        } catch (Exception $exception) {
            return;
        }

        throw new \Behat\Behat\Tester\Exception\PendingException();
    }
}

<?php

use Behat\Behat\Context\Context;

class CategoryContext implements Context
{
    protected $request;
    protected $id;

    /**
     * @When I add Category to databse
     */
    public function iAddCategoryToDatabse()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->post('http://192.168.1.100:8080/category', [
            GuzzleHttp\RequestOptions::JSON => ['name' => 'King']
        ]);
        $this->id = json_decode($response->getBody(), true)['id'];
    }

    /**
     * @When I send edit request
     */
    public function iSendEditRequest()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->put('http://192.168.1.100:8080/category', [
            GuzzleHttp\RequestOptions::JSON => ['name' => 'King2', 'id' => $this->id]
        ]);
        if ($response->getStatusCode() != 200)
        {
            throw new \Behat\Behat\Tester\Exception\PendingException();
        }
    }

    /**
     * @Then The Category was be updated
     */
    public function theCategoryWasBeUpdated()
    {

    }

    /**
     * @When I send delete request
     */
    public function iSendDeleteRequest()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->delete('http://192.168.1.100:8080/category/'.$this->id);
        if ($response->getStatusCode() != 200)
        {
            throw new \Behat\Behat\Tester\Exception\PendingException();
        }
    }

    /**
     * @Then The Category was be deleted
     */
    public function theCategoryWasBeDeleted()
    {
        throw new \Behat\Behat\Tester\Exception\PendingException();
    }
}
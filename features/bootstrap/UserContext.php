<?php

class UserContext implements \Behat\Behat\Context\Context
{
    protected $kernel;

    protected static $container;

    /**
     * FeatureContext constructor.
     *
     * @param \Symfony\Component\HttpKernel\KernelInterface $kernel
     */
    public function __construct(\Symfony\Component\HttpKernel\KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        self::$container = $this->kernel->getContainer();
    }

    /**
     * @When i have user in database
     */
    public function iHaveUserInDatabase()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->post('nginx/api/v1/user/register', [
            GuzzleHttp\RequestOptions::JSON => [
                'email' => 'testUser@123.pl',
                'username' => 'testUser',
                'plainPassword' => [
                    'first' => 'test',
                    'second' => 'test'
                ]
            ],
        ]);
        if (\Symfony\Component\HttpFoundation\Response::HTTP_OK != $response->getStatusCode()) {
            throw new \Behat\Behat\Tester\Exception\PendingException();
        }
    }

}
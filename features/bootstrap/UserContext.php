<?php

class UserContext implements \Behat\Behat\Context\Context
{
    protected $kernel;

    protected static $container;

    private static $id;

    protected static $token;

    protected static $headers;

    protected static $userToken;

    protected $client;

    /**
     * FeatureContext constructor.
     *
     * @param \Symfony\Component\HttpKernel\KernelInterface $kernel
     */
    public function __construct(\Symfony\Component\HttpKernel\KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        self::$container = $this->kernel->getContainer();
        $this->client = new \GuzzleHttp\Client();
    }

    /**
     * @When i have user in database
     */
    public function iHaveUserInDatabase()
    {
        $this->addUser();
    }

    /**
     * @When i have user token
     */
    public function iHaveUserToken()
    {
        $this->getToken();
    }

    /**
     * @When i send changeName request
     */
    public function iSendChangenameRequest()
    {
        $this->addUser();
        $this->confirmUser();
        $this->getToken();
        self::$headers = [
            'Authorization' => 'Bearer ' . self::$token,
            'Accept' => 'application/json',
        ];
        $response = $this->client->post('nginx/api/v1/user/change/username', [
            GuzzleHttp\RequestOptions::JSON => [
                'name' => 'test33.pl',
            ],
            'headers' => self::$headers,
        ]);

        if (\Symfony\Component\HttpFoundation\Response::HTTP_OK != $response->getStatusCode()) {
            throw new \Behat\Behat\Tester\Exception\PendingException();
        }
    }

    /**
     * @When i send changeEmail request
     */
    public function iSendChangeemailRequest()
    {
        $this->addUser();
        $this->confirmUser();
        $this->getToken();
        self::$headers = [
            'Authorization' => 'Bearer ' . self::$token,
            'Accept' => 'application/json',
        ];
        $response = $this->client->post('nginx/api/v1/user/change/email', [
            GuzzleHttp\RequestOptions::JSON => [
                'email' => 'testUser@wp.pl',
            ],
            'headers' => self::$headers,
        ]);

        if (\Symfony\Component\HttpFoundation\Response::HTTP_OK != $response->getStatusCode()) {
            throw new \Behat\Behat\Tester\Exception\PendingException();
        }
    }

    /**
     * @When i send changePassword request
     */
    public function iSendChangepasswordRequest()
    {
        $this->addUser();
        $this->confirmUser();
        $this->getToken();
        self::$headers = [
            'Authorization' => 'Bearer ' . self::$token,
            'Accept' => 'application/json',
        ];
        $response = $this->client->post('nginx/api/v1/user/change/password', [
            GuzzleHttp\RequestOptions::JSON => [
                "oldPassword" => "test",
                "plainPassword" => [
                    "first" => "test2",
                    "second" => "test2"
                ]
            ],
            'headers' => self::$headers,
        ]);

        if (\Symfony\Component\HttpFoundation\Response::HTTP_OK != $response->getStatusCode()) {
            throw new \Behat\Behat\Tester\Exception\PendingException();
        }
    }


    private function addUser()
    {
        $response = $this->client->post('nginx/api/v1/user/register', [
            GuzzleHttp\RequestOptions::JSON => [
                'email' => 'testUser@123.pl',
                'username' => 'testUser',
                'plainPassword' => [
                    'first' => 'test',
                    'second' => 'test',
                ],
            ],
        ]);

        if (\Symfony\Component\HttpFoundation\Response::HTTP_OK != $response->getStatusCode()) {
            throw new \Behat\Behat\Tester\Exception\PendingException();
        }
        $data = json_decode($response->getBody()->getContents(), true);
        self::$id = $data['id'];
        self::$userToken = $data['token'];
    }

    private function getToken()
    {
        $response = $this->client->post('nginx/oauth/v2/token', [
            GuzzleHttp\RequestOptions::JSON => [
                "grant_type" => "password",
                "client_id" => "3_49kosu470vacc0gso8sco8swkc444kcs0o0okow40wkc88w4w4",
                "client_secret" => "2rt5otttbjs448swo0sk44s8088k8kogwgw8ogsc44gk440c48",
                "username" => "testUser",
                "password" => "test"
            ],
        ]);

        if (\Symfony\Component\HttpFoundation\Response::HTTP_OK != $response->getStatusCode()) {
            throw new \Behat\Behat\Tester\Exception\PendingException();
        }

        self::$token = json_decode($response->getBody()->getContents(), true)['access_token'];
    }

    private function confirmUser()
    {
        $response = $this->client->post('nginx/api/v1/user/confirm/' . self::$userToken);

        if (\Symfony\Component\HttpFoundation\Response::HTTP_OK != $response->getStatusCode()) {
            throw new \Behat\Behat\Tester\Exception\PendingException();
        }
    }
}

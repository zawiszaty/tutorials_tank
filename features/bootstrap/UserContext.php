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
}

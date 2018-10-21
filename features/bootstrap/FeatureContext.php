<?php

use Behat\Behat\Context\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Elasticsearch\ClientBuilder;

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

    /**
     * @var \Elasticsearch\Client
     */
    private $client;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        self::$container = $this->kernel->getContainer();

        $this->client = ClientBuilder::fromConfig(['hosts' => ['elasticsearch:9200']], true);
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
        if (null === $this->response) {
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
        $connection->query('DELETE FROM fos_user');
        $connection->query('DELETE FROM access_token');
        $connection->query('DELETE FROM auth_code');
        $connection->query('DELETE FROM refresh_token');
        $connection->query('DELETE FROM client');
        $connection->query('INSERT INTO `fos_user` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `confirmation_token`, `password_requested_at`, `roles`, `avatar`, `steemit`, `banned`) VALUES (\'127c6fd0-be8d-11e8-a355-529269fb1459\', \'test\', \'test\', \'test@wp.pl\', \'test@wp.pl\', \'1\', NULL, \'$2y$10$IVuFoqfARqrPH9Gz.OY7Teu1CBoRuLXpFw8X4mPjy2alIgppSE9i2\', NULL, 123, NULL, \'a:1:{i:0;s:9:\"ROLE_USER\";}\', NULL, NULL, \'0\');');
        $connection->query('INSERT INTO `fos_user` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `confirmation_token`, `password_requested_at`, `roles`, `avatar`, `steemit`, `banned`) VALUES (\'127c6fd0-be8d-11e8-a355-529269fb1458\', \'test2\', \'test2\', \'test2@wp.pl\', \'test2@wp.pl\', \'1\', NULL, \'$2y$10$IVuFoqfARqrPH9Gz.OY7Teu1CBoRuLXpFw8X4mPjy2alIgppSE9i3\', NULL, 124, NULL, \'a:1:{i:0;s:10:\"ROLE_ADMIN\";}\', NULL, NULL, \'0\');');
        $connection->query('INSERT INTO `access_token` (`id`, `client_id`, `user_id`, `token`, `expires_at`, `scope`) VALUES (NULL, \'1\', \'127c6fd0-be8d-11e8-a355-529269fb1459\', \'SampleTokenNTE0YjkyNTI1ZTcxNTAxYjIzMWYwOWY3MDNjMTc5ZTA5NzU5MjA0MzdmZmU0OWIzOWY3Y2ZhZDY4NTM5OWQyMg\', NULL, NULL);');
        $connection->query('INSERT INTO `access_token` (`id`, `client_id`, `user_id`, `token`, `expires_at`, `scope`) VALUES (NULL, \'1\', \'127c6fd0-be8d-11e8-a355-529269fb1458\', \'AdminTokenNTE0YjkyNTI1ZTcxNTAxYjIzMWYwOWY3MDNjMTc5ZTA5NzU5MjA0MzdmZmU0OWIzOWY3Y2ZhZDY4NTM5OWQyMg\', NULL, NULL);');
        $connection->query('INSERT INTO `refresh_token` (`id`, `client_id`, `user_id`, `token`, `expires_at`, `scope`) VALUES (NULL, \'1\', \'127c6fd0-be8d-11e8-a355-529269fb1459\', \'SampleRefreshTokenNTElODY4ZTQyOThlNWIyMjA1ZDhmZjE1ZDYyMGMwOTUxOWM2NGFmNGRjNjQ2NDBhMDVlNGZjMmQ0YzgyNDM2Ng\', NULL, NULL);');
        $connection->query('INSERT INTO `refresh_token` (`id`, `client_id`, `user_id`, `token`, `expires_at`, `scope`) VALUES (NULL, \'1\', \'127c6fd0-be8d-11e8-a355-529269fb1458\', \'AdminRefreshTokenNTElODY4ZTQyOThlNWIyMjA1ZDhmZjE1ZDYyMGMwOTUxOWM2NGFmNGRjNjQ2NDBhMDVlNGZjMmQ0YzgyNDM2Ng\', NULL, NULL);');
        $connection->query('INSERT INTO `client` (`id`, `random_id`, `redirect_uris`, `secret`, `allowed_grant_types`) VALUES (\'3\', \'49kosu470vacc0gso8sco8swkc444kcs0o0okow40wkc88w4w4\', \'a:1:{i:0;s:9:\"localhost\";}\', \'2rt5otttbjs448swo0sk44s8088k8kogwgw8ogsc44gk440c48\', \'a:1:{i:0;s:8:\"password\";}\')');
        $connection->query('SET FOREIGN_KEY_CHECKS=1');
        $connection->commit();
        $params = ['index' => '*'];
        $response = $this->client->indices()->delete($params);
    }
}

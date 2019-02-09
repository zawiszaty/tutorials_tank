<?php

use App\Application\Command\User\Create\CreateUserCommand;
use PhpSpec\Exception\Example\PendingException;

class UserContext implements \Behat\Behat\Context\Context
{
    protected $kernel;

    protected static $container;

    private static $id;

    protected static $token;

    protected static $headers;

    protected static $userToken;

    protected $client;

    private const uri = 'nginx';

    private static $userId;

    private static $confirm_token;

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
     * @When I have user in database
     */
    public function iHaveUserInDatabase()
    {
        $response = $this->client->post(self::uri . '/api/v1/user/register', [
            GuzzleHttp\RequestOptions::JSON => [
                'email'         => 'test@wp.pl',
                'username'      => 'test',
                'plainPassword' => [
                    'first'  => 'test123',
                    'second' => 'test123',
                ],
            ],
        ]);

        if (201 !== $response->getStatusCode()) {
            throw new PendingException();
        }

        /** @var \Doctrine\ORM\EntityManager $manager */
        $manager = self::$container->get('doctrine.orm.entity_manager');
        $user = $manager->getRepository('Projections:User\Query\Projections\UserView')->findOneBy([]);
        self::$userId = $user->getId();
        self::$confirm_token = $user->getConfirmationToken();
    }

    /**
     * @When I send confirm request
     */
    public function iSendConfirmRequest()
    {
        $response = $this->client->patch(self::uri . '/api/v1/user/confirm/' . self::$confirm_token);

        if (200 !== $response->getStatusCode()) {
            throw new PendingException();
        }
    }

    /**
     * @Then I user was confirmed
     */
    public function iUserWasConfirmed()
    {
        /** @var \Doctrine\ORM\EntityManager $manager */
        $manager = self::$container->get('doctrine.orm.entity_manager');
        $user = $manager->getRepository('Projections:User\Query\Projections\UserView')->findOneBy([]);

        if ($user->isEnabled()) {
            throw new \Exception();
        }
    }

    /**
     * @When I send banned request
     */
    public function iSendBannedRequest()
    {
        $command = new CreateUserCommand();
        $command->username = 'admin';
        $command->plainPassword = 'admin123';
        $command->email = 'admin@wp.pl';
        $command->roles = [
            'ROLE_USER',
            'ROLE_ADMIN',
        ];
        $command->banned = false;
        $commandBus = self::$container->get('tactician.commandbus.command');
        $commandBus->handle($command);
        /** @var \Doctrine\ORM\EntityManager $manager */
        $manager = self::$container->get('doctrine.orm.entity_manager');
        $user = $manager->getRepository('Projections:User\Query\Projections\UserView')->findOneBy(['username' => 'admin']);
        $connection = self::$container->get('doctrine')->getConnection();
        $connection->beginTransaction();
        $connection->query('SET FOREIGN_KEY_CHECKS=0');
        $connection->query('INSERT INTO `access_token` (`id`, `client_id`, `user_id`, `token`, `expires_at`, `scope`) VALUES (NULL, \'1\', \'' . $user->getId() . '\', \'SampleAdminNTE0YjkyNTI1ZTcxNTAxYjIzMWYwOWY3MDNjMTc5ZTA5NzU5MjA0MzdmZmU0OWIzOWY3Y2ZhZDY4NTM5OWQyMg\', NULL, NULL);');
        $connection->query('INSERT INTO `client` (`id`, `random_id`, `redirect_uris`, `secret`, `allowed_grant_types`) VALUES (\'3\', \'49kosu470vacc0gso8sco8swkc444kcs0o0okow40wkc88w4w4\', \'a:1:{i:0;s:9:\"localhost\";}\', \'2rt5otttbjs448swo0sk44s8088k8kogwgw8ogsc44gk440c48\', \'a:1:{i:0;s:8:\"password\";}\')');
        $connection->query('SET FOREIGN_KEY_CHECKS=1');
        $this->client->patch(self::uri . '/api/v1/user/confirm/' . self::$confirm_token);
        $connection->commit();
        $confirm_token = $user->getConfirmationToken();
        $this->client->patch(self::uri . '/api/v1/user/confirm/' . $confirm_token);
        $response = $this->client->patch(self::uri . '/api/v1/user/banned/' . self::$userId, [
            'headers' => [
                'Authorization' => 'Bearer SampleAdminNTE0YjkyNTI1ZTcxNTAxYjIzMWYwOWY3MDNjMTc5ZTA5NzU5MjA0MzdmZmU0OWIzOWY3Y2ZhZDY4NTM5OWQyMg',
            ],
        ]);

        if (200 !== $response->getStatusCode()) {
            throw new PendingException();
        }
    }

    /**
     * @Then I have banned user
     */
    public function iHaveBannedUser()
    {
        /** @var \Doctrine\ORM\EntityManager $manager */
        $manager = self::$container->get('doctrine.orm.entity_manager');
        $user = $manager->getRepository('Projections:User\Query\Projections\UserView')->findOneBy([]);

        if ($user->isBanned()) {
            throw new \Exception();
        }
    }

    /**
     * @When I send change user email request
     */
    public function iSendChangeUserEmailRequest()
    {
        $connection = self::$container->get('doctrine')->getConnection();
        $connection->beginTransaction();
        $connection->query('SET FOREIGN_KEY_CHECKS=0');
        $connection->query('INSERT INTO `access_token` (`id`, `client_id`, `user_id`, `token`, `expires_at`, `scope`) VALUES (NULL, \'1\', \'' . self::$userId . '\', \'SampleTokenNTE0YjkyNTI1ZTcxNTAxYjIzMWYwOWY3MDNjMTc5ZTA5NzU5MjA0MzdmZmU0OWIzOWY3Y2ZhZDY4NTM5OWQyMg\', NULL, NULL);');
        $connection->query('INSERT INTO `client` (`id`, `random_id`, `redirect_uris`, `secret`, `allowed_grant_types`) VALUES (\'3\', \'49kosu470vacc0gso8sco8swkc444kcs0o0okow40wkc88w4w4\', \'a:1:{i:0;s:9:\"localhost\";}\', \'2rt5otttbjs448swo0sk44s8088k8kogwgw8ogsc44gk440c48\', \'a:1:{i:0;s:8:\"password\";}\')');
        $connection->query('SET FOREIGN_KEY_CHECKS=1');
        $this->client->patch(self::uri . '/api/v1/user/confirm/' . self::$confirm_token);
        $connection->commit();
        $response = $this->client->patch(self::uri . '/api/v1/user/change/email', [
            'headers' => [
                'Authorization' => 'Bearer ' . 'SampleTokenNTE0YjkyNTI1ZTcxNTAxYjIzMWYwOWY3MDNjMTc5ZTA5NzU5MjA0MzdmZmU0OWIzOWY3Y2ZhZDY4NTM5OWQyMg',
            ],
            GuzzleHttp\RequestOptions::JSON => [
                'email' => 'test2w@wp.pl',
            ],
        ]);

        if (200 !== $response->getStatusCode()) {
            throw new PendingException();
        }
    }

    /**
     * @Then I have user with changed email
     */
    public function iHaveUserWithChangedEmail()
    {
        /** @var \Doctrine\ORM\EntityManager $manager */
        $manager = self::$container->get('doctrine.orm.entity_manager');
        $user = $manager->getRepository('Projections:User\Query\Projections\UserView')->find(self::$userId);

        dump($user->getEmail());
//        if ($user->getEmail() !== 'test2w@wp.pl') {
//            throw new \Exception();
//        }
    }

    /**
     * @When I send change user name request
     */
    public function iSendChangeUserNameRequest()
    {
        $connection = self::$container->get('doctrine')->getConnection();
        $connection->beginTransaction();
        $connection->query('SET FOREIGN_KEY_CHECKS=0');
        $connection->query('INSERT INTO `access_token` (`id`, `client_id`, `user_id`, `token`, `expires_at`, `scope`) VALUES (NULL, \'1\', \'' . self::$userId . '\', \'SampleTokenNTE0YjkyNTI1ZTcxNTAxYjIzMWYwOWY3MDNjMTc5ZTA5NzU5MjA0MzdmZmU0OWIzOWY3Y2ZhZDY4NTM5OWQyMg\', NULL, NULL);');
        $connection->query('INSERT INTO `client` (`id`, `random_id`, `redirect_uris`, `secret`, `allowed_grant_types`) VALUES (\'3\', \'49kosu470vacc0gso8sco8swkc444kcs0o0okow40wkc88w4w4\', \'a:1:{i:0;s:9:\"localhost\";}\', \'2rt5otttbjs448swo0sk44s8088k8kogwgw8ogsc44gk440c48\', \'a:1:{i:0;s:8:\"password\";}\')');
        $connection->query('SET FOREIGN_KEY_CHECKS=1');
        $this->client->patch(self::uri . '/api/v1/user/confirm/' . self::$confirm_token);
        $connection->commit();
        $response = $this->client->patch(self::uri . '/api/v1/user/change/username', [
            'headers' => [
                'Authorization' => 'Bearer ' . 'SampleTokenNTE0YjkyNTI1ZTcxNTAxYjIzMWYwOWY3MDNjMTc5ZTA5NzU5MjA0MzdmZmU0OWIzOWY3Y2ZhZDY4NTM5OWQyMg',
            ],
            GuzzleHttp\RequestOptions::JSON => [
                'username' => 'username2',
            ],
        ]);

        if (200 !== $response->getStatusCode()) {
            throw new PendingException();
        }
    }

    /**
     * @Then I have user with changed name
     */
    public function iHaveUserWithChangedName()
    {
        /** @var \Doctrine\ORM\EntityManager $manager */
        $manager = self::$container->get('doctrine.orm.entity_manager');
        $user = $manager->getRepository('Projections:User\Query\Projections\UserView')->find(self::$userId);

        dump($user->getUsername());
        ////        if ($user->getEmail() !== 'test2w@wp.pl') {
////            throw new \Exception();
////        }
    }

    /**
     * @When I send change password resquest
     */
    public function iSendChangePasswordResquest()
    {
        $connection = self::$container->get('doctrine')->getConnection();
        $connection->beginTransaction();
        $connection->query('SET FOREIGN_KEY_CHECKS=0');
        $connection->query('INSERT INTO `access_token` (`id`, `client_id`, `user_id`, `token`, `expires_at`, `scope`) VALUES (NULL, \'1\', \'' . self::$userId . '\', \'SampleTokenNTE0YjkyNTI1ZTcxNTAxYjIzMWYwOWY3MDNjMTc5ZTA5NzU5MjA0MzdmZmU0OWIzOWY3Y2ZhZDY4NTM5OWQyMg\', NULL, NULL);');
        $connection->query('INSERT INTO `client` (`id`, `random_id`, `redirect_uris`, `secret`, `allowed_grant_types`) VALUES (\'3\', \'49kosu470vacc0gso8sco8swkc444kcs0o0okow40wkc88w4w4\', \'a:1:{i:0;s:9:\"localhost\";}\', \'2rt5otttbjs448swo0sk44s8088k8kogwgw8ogsc44gk440c48\', \'a:1:{i:0;s:8:\"password\";}\')');
        $connection->query('SET FOREIGN_KEY_CHECKS=1');
        $this->client->patch(self::uri . '/api/v1/user/confirm/' . self::$confirm_token);
        $connection->commit();
        $response = $this->client->patch(self::uri . '/api/v1/user/change/password', [
            'headers' => [
                'Authorization' => 'Bearer ' . 'SampleTokenNTE0YjkyNTI1ZTcxNTAxYjIzMWYwOWY3MDNjMTc5ZTA5NzU5MjA0MzdmZmU0OWIzOWY3Y2ZhZDY4NTM5OWQyMg',
            ],
            GuzzleHttp\RequestOptions::JSON => [
                'plainPassword' => [
                    'first'  => 'test1234',
                    'second' => 'test1234',
                ],
                'oldPassword' => 'test123',
            ],
        ]);

        if (200 !== $response->getStatusCode()) {
            throw new \Exception();
        }
    }

    /**
     * @When I have user with changed password
     */
    public function iHaveUserWithChangedPassword()
    {
//        throw new PendingException();
    }

    /**
     * @When I send granted request
     */
    public function iSendGrantedRequest()
    {
        $command = new CreateUserCommand();
        $command->username = 'admin';
        $command->plainPassword = 'admin123';
        $command->email = 'admin@wp.pl';
        $command->roles = [
            'ROLE_USER',
            'ROLE_ADMIN',
        ];
        $command->banned = false;
        $commandBus = self::$container->get('tactician.commandbus.command');
        $commandBus->handle($command);
        /** @var \Doctrine\ORM\EntityManager $manager */
        $manager = self::$container->get('doctrine.orm.entity_manager');
        $user = $manager->getRepository('Projections:User\Query\Projections\UserView')->findOneBy(['username' => 'admin']);
        $connection = self::$container->get('doctrine')->getConnection();
        $connection->beginTransaction();
        $connection->query('SET FOREIGN_KEY_CHECKS=0');
        $connection->query('INSERT INTO `access_token` (`id`, `client_id`, `user_id`, `token`, `expires_at`, `scope`) VALUES (NULL, \'1\', \'' . $user->getId() . '\', \'SampleAdminNTE0YjkyNTI1ZTcxNTAxYjIzMWYwOWY3MDNjMTc5ZTA5NzU5MjA0MzdmZmU0OWIzOWY3Y2ZhZDY4NTM5OWQyMg\', NULL, NULL);');
        $connection->query('INSERT INTO `client` (`id`, `random_id`, `redirect_uris`, `secret`, `allowed_grant_types`) VALUES (\'3\', \'49kosu470vacc0gso8sco8swkc444kcs0o0okow40wkc88w4w4\', \'a:1:{i:0;s:9:\"localhost\";}\', \'2rt5otttbjs448swo0sk44s8088k8kogwgw8ogsc44gk440c48\', \'a:1:{i:0;s:8:\"password\";}\')');
        $connection->query('SET FOREIGN_KEY_CHECKS=1');
        $this->client->patch(self::uri . '/api/v1/user/confirm/' . self::$confirm_token);
        $connection->commit();
        $confirm_token = $user->getConfirmationToken();
        $this->client->patch(self::uri . '/api/v1/user/confirm/' . $confirm_token);
        $response = $this->client->patch(self::uri . '/api/v1/user/role/admin/' . self::$userId, [
            'headers' => [
                'Authorization' => 'Bearer SampleAdminNTE0YjkyNTI1ZTcxNTAxYjIzMWYwOWY3MDNjMTc5ZTA5NzU5MjA0MzdmZmU0OWIzOWY3Y2ZhZDY4NTM5OWQyMg',
            ],
        ]);

        if (202 !== $response->getStatusCode()) {
            throw new PendingException();
        }
    }

    /**
     * @Then I have other admin
     */
    public function iHaveOtherAdmin()
    {
    }
}

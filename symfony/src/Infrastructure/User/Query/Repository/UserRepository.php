<?php

namespace App\Infrastructure\User\Query\Repository;

use App\Infrastructure\User\Query\Projections\UserView;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class UserRepository.
 */
class UserRepository extends ServiceEntityRepository implements UserLoaderInterface, UserProviderInterface
{
    /**
     * UserRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserView::class);
    }

    /**
     * @param string $username
     *
     * @return int|UserInterface|null
     */
    public function loadUserByUsername($username)
    {
        return $this->fetchUser($username);
    }

    /**
     * @param UserInterface $user
     *
     * @return int|UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof UserView) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        $username = $user->getUsername();

        return $this->fetchUser($username);
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return UserView::class === $class;
    }

    /**
     * @param $username
     *
     * @return int
     */
    private function fetchUser($username)
    {
        // make a call to your webservice here
        $userData = $this->createQueryBuilder('u')
            ->where('u.username = :name')
            ->setParameter('name', $username)
            ->getFirstResult();
        // pretend it returns an array on success, false if there is no user

        if ($userData) {
            $password = '...';

            // ...
            dump($userData);
            die();

            return $userData;
        }

        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $username)
        );
    }
}

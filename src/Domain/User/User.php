<?php

namespace App\Domain\User;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\User\Assert\UserIsNotAdmin;
use App\Domain\User\Event\UserAvatarWasChanged;
use App\Domain\User\Event\UserMailWasChanged;
use App\Domain\User\Event\UserNameWasChanged;
use App\Domain\User\Event\UserPasswordWasChanged;
use App\Domain\User\Event\UserWasAdminRoleGranted;
use App\Domain\User\Event\UserWasBanned;
use App\Domain\User\Event\UserWasConfirmed;
use App\Domain\User\Event\UserWasCreated;
use App\Domain\User\ValueObject\Avatar;
use App\Domain\User\ValueObject\ConfirmationToken;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Password;
use App\Domain\User\ValueObject\Roles;
use App\Domain\User\ValueObject\Steemit;
use App\Domain\User\ValueObject\UserName;
use Broadway\EventSourcing\EventSourcedAggregateRoot;

/**
 * Class User.
 */
class User extends EventSourcedAggregateRoot
{
    /**
     * @var AggregateRootId
     */
    private $id;

    /**
     * @var UserName
     */
    private $username;

    /**
     * @var Email
     */
    private $email;

    /**
     * @var Roles
     */
    private $roles;

    /**
     * @var Avatar
     */
    private $avatar;

    /**
     * @var Steemit
     */
    private $steemit;

    /**
     * @var bool
     */
    private $banned;

    /**
     * @var bool
     */
    private $enabled;

    /**
     * @var Password
     */
    private $password;

    /**
     * @var ConfirmationToken
     */
    private $confirmationToken;

    /**
     * @return array
     */
    public function serialize()
    {
        return [
            'id' => $this->id->toString(),
            'username' => $this->username->toString(),
            'email' => $this->email->toString(),
            'roles' => $this->roles->toArray(),
            'steemit' => $this->steemit->toString(),
            'banned' => $this->banned,
            'password' => $this->password,
            'enabled' => $this->enabled,
            'confirmationToken' => $this->confirmationToken,
        ];
    }

    /**
     * @param array $params
     *
     * @return User
     *
     * @throws \Assert\AssertionFailedException
     */
    public static function fromString(array $params): self
    {
        $self = new self();
        $self->id = AggregateRootId::fromString($params['id']);
        $self->username = UserName::fromString($params['username']);
        $self->email = Email::fromString($params['email']);
        $self->roles = Roles::fromString($params['roles']);
        $self->steemit = Steemit::fromString($params['steemit']);
        $self->banned = $params['banned'];
        $self->password = Password::fromString($params['password']);
        $self->enabled = $params['enabled'];
        $self->confirmationToken = ConfirmationToken::fromString($params['confirmationToken']);

        return $self;
    }

    /**
     * @param AggregateRootId   $id
     * @param UserName          $username
     * @param Email             $email
     * @param Roles             $roles
     * @param Avatar            $avatar
     * @param Steemit           $steemit
     * @param bool              $banned
     * @param Password          $password
     * @param ConfirmationToken $confirmationToken
     *
     * @return mixed
     */
    public static function create(
        AggregateRootId $id,
        UserName $username,
        Email $email,
        Roles $roles,
        Avatar $avatar,
        Steemit $steemit,
        bool $banned,
        Password $password,
        ConfirmationToken $confirmationToken
    ) {
        $user = new self();
        $user->apply(new UserWasCreated($id, $username, $email, $roles, $avatar, $steemit, $banned, $password, false, $confirmationToken));

        return $user;
    }

    /**
     * @param string $name
     *
     * @throws \Assert\AssertionFailedException
     */
    public function changeName(string $name)
    {
        $this->apply(new UserNameWasChanged(
            $this->getId(),
            UserName::fromString($name),
            $this->getEmail(),
            $this->getRoles(),
            $this->getAvatar(),
            $this->getSteemit(),
            $this->isBanned(),
            $this->isEnabled()
        ));
    }

    /**
     * @param string $email
     *
     * @throws \Assert\AssertionFailedException
     */
    public function changeEmail(string $email)
    {
        $this->apply(new UserMailWasChanged(
            $this->getId(),
            Email::fromString($email)
        ));
    }

    public function applyUserMailWasChanged(UserMailWasChanged $event)
    {
        $this->email = $event->getEmail();
        $this->enabled = false;
    }

    public function applyUserNameWasChanged(UserNameWasChanged $event)
    {
        $this->username = $event->getUsername();
    }

    public function confirm()
    {
        $this->apply(new UserWasConfirmed($this->id, true));
    }

    public function banned()
    {
        $this->apply(new UserWasBanned($this->id, true));
    }

    public function applyUserWasBanned(UserWasBanned $userWasBanned)
    {
        $this->banned = $userWasBanned->isBanned();
    }

    /**
     * @param UserWasCreated $userWasCreated
     */
    public function applyUserWasCreated(UserWasCreated $userWasCreated): void
    {
        $this->id = $userWasCreated->getId();
        $this->username = $userWasCreated->getUsername();
        $this->email = $userWasCreated->getEmail();
        $this->roles = $userWasCreated->getRoles();
        $this->avatar = $userWasCreated->getAvatar();
        $this->steemit = $userWasCreated->getSteemit();
        $this->banned = $userWasCreated->isBanned();
        $this->enabled = $userWasCreated->isEnabled();
        $this->confirmationToken = $userWasCreated->getConfirmationToken();
    }

    public function changePassword(string $password)
    {
        $this->apply(new UserPasswordWasChanged(
            $this->id,
            Password::fromString($password)
        ));
    }

    public function applyUserPasswordWasChanged(UserPasswordWasChanged $event)
    {
        $this->password = $event->getPassword();
    }

    /**
     * @param UserWasConfirmed $userWasConfirmed
     */
    public function applyUserWasConfirmed(UserWasConfirmed $userWasConfirmed): void
    {
        $this->enabled = $userWasConfirmed->isEnabled();
    }

    /**
     * @param string $avatar
     */
    public function changeAvatar(string $avatar)
    {
        $this->apply(new UserAvatarWasChanged(
            $this->id,
            Avatar::fromString($avatar)
        ));
    }

    /**
     * @param UserAvatarWasChanged $event
     */
    public function applyUserAvatarWasChanged(UserAvatarWasChanged $event)
    {
        $this->avatar = $event->getAvatar();
    }

    /**
     * @return AggregateRootId
     */
    public function getId(): AggregateRootId
    {
        return $this->id;
    }

    /**
     * @return UserName
     */
    public function getUsername(): UserName
    {
        return $this->username;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return Roles
     */
    public function getRoles(): Roles
    {
        return $this->roles;
    }

    /**
     * @return Avatar
     */
    public function getAvatar(): Avatar
    {
        return $this->avatar;
    }

    /**
     * @return Steemit
     */
    public function getSteemit(): Steemit
    {
        return $this->steemit;
    }

    /**
     * @return bool
     */
    public function isBanned(): bool
    {
        return $this->banned;
    }

    public function getAggregateRootId(): string
    {
        return $this->id->toString();
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @return Password
     */
    public function getPassword(): Password
    {
        return $this->password;
    }

    /**
     * @return ConfirmationToken
     */
    public function getConfirmationToken(): ConfirmationToken
    {
        return $this->confirmationToken;
    }

    /**
     * @throws Exception\UserIsAdminException
     */
    public function grantedAdminRole(): void
    {
        UserIsNotAdmin::check($this->roles);
        $this->apply(new UserWasAdminRoleGranted($this->id));
    }

    /**
     * @param UserWasAdminRoleGranted $adminRoleGranted
     *
     * @throws \Exception
     */
    public function applyUserWasAdminRoleGranted(UserWasAdminRoleGranted $adminRoleGranted): void
    {
        $this->roles->appendRole('ROLE_ADMIN');
    }
}

<?php

namespace App\Domain\User;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\User\Assert\UserIsAdmin;
use App\Domain\User\Assert\UserIsNotAdmin;
use App\Domain\User\Event\UserAvatarWasChanged;
use App\Domain\User\Event\UserMailWasChanged;
use App\Domain\User\Event\UserNameWasChanged;
use App\Domain\User\Event\UserPasswordWasChanged;
use App\Domain\User\Event\UserUnBann;
use App\Domain\User\Event\UserWasAdminRoleGranted;
use App\Domain\User\Event\UserWasAdminUnGranted;
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
            'id'                => $this->id->toString(),
            'username'          => $this->username->toString(),
            'email'             => $this->email->toString(),
            'roles'             => $this->roles->toArray(),
            'steemit'           => $this->steemit->toString(),
            'banned'            => $this->banned,
            'password'          => $this->password,
            'enabled'           => $this->enabled,
            'confirmationToken' => $this->confirmationToken,
        ];
    }

    /**
     * @throws \Assert\AssertionFailedException
     *
     * @return User
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

    public function applyUserWasConfirmed(UserWasConfirmed $userWasConfirmed): void
    {
        $this->enabled = $userWasConfirmed->isEnabled();
    }

    public function changeAvatar(string $avatar)
    {
        $this->apply(new UserAvatarWasChanged(
            $this->id,
            Avatar::fromString($avatar)
        ));
    }

    public function applyUserAvatarWasChanged(UserAvatarWasChanged $event)
    {
        $this->avatar = $event->getAvatar();
    }

    public function getId(): AggregateRootId
    {
        return $this->id;
    }

    public function getUsername(): UserName
    {
        return $this->username;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getRoles(): Roles
    {
        return $this->roles;
    }

    public function getAvatar(): Avatar
    {
        return $this->avatar;
    }

    public function getSteemit(): Steemit
    {
        return $this->steemit;
    }

    public function isBanned(): bool
    {
        return $this->banned;
    }

    public function getAggregateRootId(): string
    {
        return $this->id->toString();
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }

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
     * @throws \Exception
     */
    public function applyUserWasAdminRoleGranted(UserWasAdminRoleGranted $adminRoleGranted): void
    {
        $this->roles->appendRole('ROLE_ADMIN');
    }

    /**
     * @throws Exception\UserIsAdminException
     * @throws Exception\UserNotIsAdminException
     */
    public function unGrantedAdminRole(): void
    {
        UserIsAdmin::check($this->roles);
        $this->apply(new UserWasAdminUnGranted($this->id));
    }

    public function applyUserWasAdminUnGranted(UserWasAdminUnGranted $userWasAdminUnGranted): void
    {
        $this->roles->unAppendRole('ROLE_ADMIN');
    }

    public function unBann()
    {
        $this->apply(new UserUnBann($this->id, false));
    }

    public function applyUserUnBann(UserUnBann $userUnBann)
    {
        $this->banned = false;
    }
}

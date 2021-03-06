<?php

namespace App\Infrastructure\User\Query\Projections;

use App\Infrastructure\Comment\Query\Projections\CommentView;
use App\Infrastructure\Message\MessageView;
use App\Infrastructure\Notification\NotificationView;
use App\Infrastructure\Post\Query\Projections\PostView;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * Class UserView.
 */
class UserView extends BaseUser
{
    /**
     * @var string
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * @var string
     */
    protected $avatar;

    /**
     * @var string
     */
    protected $steemit;

    /**
     * @var bool
     */
    protected $banned;

    /**
     * @var CommentView
     */
    protected $comment;

    /**
     * @var PostView
     */
    protected $post;

    /**
     * @var MessageView
     */
    protected $message;

    /**
     * @var NotificationView
     */
    protected $notification;

    /**
     * @var string
     */
    protected $ban;

    /**
     * @return string
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     */
    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * @return string
     */
    public function getSteemit(): ?string
    {
        return $this->steemit;
    }

    public function setSteemit(string $steemit): void
    {
        $this->steemit = $steemit;
    }

    /**
     * @return bool
     */
    public function isBanned(): ?bool
    {
        return $this->banned;
    }

    /**
     * @param bool $banned
     */
    public function setBanned(?bool $banned): void
    {
        $this->banned = $banned;
    }

    /**
     * @throws \Exception
     *
     * @return UserView
     */
    public static function deserializeProjections(array $data)
    {
        $userView = new self();
        $userView->id = $data['id'];
        $userView->username = $data['username'];
        $userView->avatar = $data['avatar'];
        $userView->steemit = $data['steemit'];
        $userView->banned = $data['banned'];
        $userView->email = $data['email'];
        $userView->emailCanonical = strtolower($data['email']);
        $userView->password = $data['password'];
        $userView->roles = $data['roles'];
        $userView->enabled = $data['enabled'];
        $userView->confirmationToken = $data['confirmationToken'];

        return $userView;
    }

    public function confirmed(): void
    {
        $this->enabled = true;
    }

    public function banned(): void
    {
        $this->banned = true;
    }

    public function unBan()
    {
        $this->banned = false;
    }

    public function changeName(string $name): void
    {
        $this->username = $name;
    }

    public function changeMail(string $mail): void
    {
        $this->email = $mail;
        $this->enabled = false;
    }

    public function changePassword(string $password): void
    {
        $this->password = $password;
    }

    public function changeAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * @return bool
     */

    /**
     * @return bool
     */
    public function getBan()
    {
        return $this->banned;
    }

    public function appendRole(string $role): void
    {
        array_push($this->roles, $role);
    }

    /**
     * @throws \Exception
     */
    public function unAppendRole(string $role)
    {
        if (!\in_array($role, $this->roles)) {
            throw new \Exception();
        }

        foreach ($this->roles as $key => $item) {
            if ($item === $role) {
                unset($this->roles[$key]);
            }
        }
    }

    public function serializeProjections(): array
    {
        return [
            'id'       => $this->id,
            'username' => $this->username,
            'avatar'   => $this->avatar,
            'steemit'  => $this->steemit,
            'banned'   => $this->banned,
            'enabled'  => $this->enabled,
            'roles'    => serialize($this->roles),
        ];
    }
}

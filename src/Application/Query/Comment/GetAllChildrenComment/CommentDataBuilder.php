<?php

namespace App\Application\Query\Comment\GetAllChildrenComment;

use App\Infrastructure\Comment\Query\CommentRepositoryElastic;
use App\Infrastructure\User\Repository\UserRepositoryElastic;

/**
 * Class CommentDataBuilder.
 */
class CommentDataBuilder
{
    /**
     * @var UserRepositoryElastic
     */
    private $repositoryElastic;

    /**
     * @var CommentRepositoryElastic
     */
    private $commentRepositoryElastic;

    private static $comments;

    /**
     * CommentDataBuilder constructor.
     *
     * @param UserRepositoryElastic    $repositoryElastic
     * @param CommentRepositoryElastic $commentRepositoryElastic
     */
    public function __construct(
        UserRepositoryElastic $repositoryElastic,
        CommentRepositoryElastic $commentRepositoryElastic
    ) {
        $this->repositoryElastic = $repositoryElastic;
        $this->commentRepositoryElastic = $commentRepositoryElastic;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function build(array $data)
    {
        foreach ($data as $index => $item) {
            $user = $this->repositoryElastic->get($item['user'])['_source'];
            $data[$index]['user'] = [
                'avatar'   => $user['avatar'],
                'username' => $user['username'],
            ];
        }

        return $data;
    }
}

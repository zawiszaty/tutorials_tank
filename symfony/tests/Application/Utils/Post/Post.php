<?php
/**
 * Created by PhpStorm.
 * User: zawiszaty
 * Date: 10.02.19
 * Time: 13:08.
 */

namespace App\Tests\Application\Utils\Post;

use App\Application\Command\Post\Create\CreatePostCommand;
use App\Infrastructure\Category\Query\Projections\CategoryView;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class Post.
 */
class Post
{
    /**
     * @param string       $content
     * @param string       $user
     * @param CategoryView $category
     *
     * @return CreatePostCommand
     */
    public static function create(string $content, string $user, CategoryView $category): CreatePostCommand
    {
        copy('public/sample/sample.jpg', 'public/sample/sample2.jpg');
        $file = new File('public/sample/sample2.jpg');
        $command = new CreatePostCommand();
        $command->setContent($content);
        $command->setUser($user);
        $command->setType('oder_site');
        $command->setTitle('test');
        $command->setFile($file);
        $command->setThumbnail('test');
        $command->setCategory($category);
        $command->setShortDescription('test');

        return $command;
    }
}

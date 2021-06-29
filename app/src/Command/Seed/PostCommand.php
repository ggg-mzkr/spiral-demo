<?php
/**
 * {project-name}
 *
 * @author {author-name}
 */
declare(strict_types=1);

namespace App\Command\Seed;

use Faker\Generator;
use Spiral\Console\Command;
use App\Repository\UserRepository;
use App\Service\PostService as PostService;

class PostCommand extends Command
{
    protected const NAME = 'seed:post';
    /** @var UserRepository */
    private $users;
    /** @var PostService */
    private $postService;
    /**
     * @param UserRepository $users
     * @param PostService $postService
     * @param string|null $name
     */
    public function __construct(PostService $postService, UserRepository $users, ?string $name = null)
    {
        parent::__construct($name);
        $this->postService = $postService;
        $this->users = $users;
    }

    protected function perform(Generator $faker): void
    {
        $users = $this->users->findAll();

        for ($i = 0; $i < 1000; $i++) {
            $user = $users[array_rand($users)];

            $post = $this->postService->createPost(
                $user,
                $faker->sentence(12),
                $faker->text(900)
            );

            $this->sprintf("New post: <info>%s</info>\n", $post->title);
        }
    }
}

<?php
/**
 * {project-name}
 *
 * @author {author-name}
 */
declare(strict_types=1);

namespace App\Controller;

use App\Database\Post;
use App\Request\CommentRequest;
use Psr\Http\Message\ResponseInterface;
use Spiral\DataGrid\GridFactory;
use Spiral\Http\Exception\ClientException\NotFoundException;
use Spiral\Router\Annotation\Route;
use App\View\PostView;
use App\Repository\PostRepository;
use App\View\PostGrid;
use App\Service\CommentService;
use App\Repository\UserRepository;
use Spiral\Views\ViewsInterface;

class PostController
{
    /** @var PostView */
    private $postView;
    /** @var PostRepository */
    private $posts;
    /** @var PostGrid */
    private $postGrid;
    /** @var CommentService */
    private $commentService;
    /** @var UserRepository */
    private $users;
    /** @var ViewsInterface */
    private $views;
    /**
     * @param PostView $postView
     * @param PostRepository $posts
     * @param PostGrid $postGrid
     * @param CommentService $commentService
     * @param UserRepository $users
     * @param ViewsInterface $views
     */
    public function __construct(ViewsInterface $views, UserRepository $users, CommentService $commentService, PostGrid $postGrid, PostRepository $posts, PostView $postView)
    {
        $this->views = $views;
        $this->users = $users;
        $this->commentService = $commentService;
        $this->postGrid = $postGrid;
        $this->posts = $posts;
        $this->postView = $postView;
    }
    /**
     * @Route(route="/api/post/<post:\d+>", name="post.get", methods="GET")
     * @param Post $post
     * @return ResponseInterface
     */
    public function get(Post $post)
    {
        return $this->postView->json($post);
    }

    /**
     * @Route(route="/api/post", name="post.list", methods="GET")
     * @param GridFactory $grids
     * @return array
     */
    public function list(GridFactory $grids): array
    {
        $grid = $grids->create($this->posts->findAllWithAuthor(), $this->postGrid);

        return [
            'posts' => array_map(
                [$this->postView, 'map'],
                iterator_to_array($grid->getIterator())
            )
        ];
    }

    /**
     * @Route(route="/api/post/<post:\d+>/comment", name="post.comment", methods="POST")
     * @param Post          $post
     * @param CommentRequest $commentRequest
     * @return array
     */
    public function comment(Post $post, CommentRequest $commentRequest)
    {
        $this->commentService->comment(
            $post,
            $this->users->findOne(),
            $commentRequest->getMessage()
        );

        return ['status' => 201];
    }

    /**
     * @Route(route="/posts", name="post.all", methods="GET")
     * @param GridFactory $grids
     * @return string
     */
    public function all(GridFactory $grids): string
    {
        $grid = $grids->create($this->posts->findAllWithAuthor(), $this->postGrid);

        return $this->views->render('posts', ['posts' => $grid]);
    }

    /**
     * @Route(route="/post/<id:\d+>", name="post.view", methods="GET")
     * @param string $id
     * @return string
     */
    public function view(string $id): string
    {
        $post = $this->posts->findOneWithComments($id);
        if ($post === null) {
            throw new NotFoundException();
        }

        return $this->views->render('post', ['post' => $post]);
    }

}

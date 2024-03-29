<?php

namespace App\View;

use App\Database\Post;
use Psr\Http\Message\ResponseInterface;
use Spiral\Core\Container\SingletonInterface;
use Spiral\Prototype\Annotation\Prototyped;
use Spiral\Http\ResponseWrapper;

/**
 * @Prototyped(property="postView")
 */
class PostView implements SingletonInterface
{
    /** @var ResponseWrapper */
    private $response;
    /**
     * @param ResponseWrapper $response
     */
    public function __construct(ResponseWrapper $response)
    {
        $this->response = $response;
    }
    public function map(Post $post): array
    {
        return [
            'post' => [
                'id'      => $post->id,
                'author'  => [
                    'id'   => $post->author->id,
                    'name' => $post->author->name
                ],
                'title'   => $post->title,
                'content' => $post->content,
            ]
        ];
    }

    public function json(Post $post): ResponseInterface
    {
        return $this->response->json($this->map($post), 200);
    }
}

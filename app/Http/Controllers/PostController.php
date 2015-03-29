<?php
/**
 * Controller to handle requests operating against single posts.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2014, MyBB Group
 * @license   http://www.mybb.com/about/license GNU LESSER GENERAL PUBLIC LICENSE
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use MyBB\Auth\Contracts\Guard;
use MyBB\Core\Database\Repositories\IPostRepository;
use MyBB\Core\Http\Requests\Post\LikePostRequest;
use MyBB\Core\Likes\Database\Repositories\LikesRepositoryInterface;

class PostController extends Controller
{
    /**
     * @var IPostRepository $postsRepository
     */
    private $postsRepository;
    /**
     * @var LikesRepositoryInterface $likesRepository
     */
    private $likesRepository;

    /**
     * @param Guard                    $guard
     * @param Request                  $request
     * @param IPostRepository          $postRepository
     * @param LikesRepositoryInterface $likesRepository
     */
    public function __construct(Guard $guard, Request $request, IPostRepository $postRepository, LikesRepositoryInterface $likesRepository)
    {
        parent::__construct($guard, $request);
        $this->postsRepository = $postRepository;
        $this->likesRepository = $likesRepository;
    }

    public function postToggleLike(LikePostRequest $request, Redirector $redirector)
    {
        $post = $this->postsRepository->find($request->get('post_id'));

        $like = $this->likesRepository->toggleLikeForContent($post);

        $redirect = $redirector->route('topics.showPost',
            [
                'slug' => $post->topic->slug,
                'id' => $post->topic_id,
                'postId' => $post->id,
            ]
        );

        if ($like === null) {
            $redirect = $redirect->withSuccess(trans('post.like_removed'));
        } else {
            $redirect = $redirect->withSuccess(trans('post.like_added'));
        }

        return $redirect;
    }
}

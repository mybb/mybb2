<?php

namespace MyBB\Core\Moderation\Moderations;

use MyBB\Core\Database\Models\Post;
use MyBB\Core\Database\Repositories\PostRepositoryInterface;
use MyBB\Core\Moderation\ModerationInterface;

class DeletePost implements ModerationInterface
{
    /**
     * @var PostRepositoryInterface
     */
    protected $postRepository;

    /**
     * @param PostRepositoryInterface $postRepository
     */
    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return 'delete_post';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Delete';
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return 'fa-trash-o';
    }

    /**
     * @param Post $post
     */
    public function deletePost(Post $post)
    {
        $this->postRepository->deletePost($post);
    }

    /**
     * @param mixed $content
     * @param array $options
     *
     * @return mixed
     */
    public function apply($content, array $options = [])
    {
        $this->deletePost($content);
    }

    /**
     * @param mixed $content
     * @param array $options
     *
     * @return bool
     */
    public function supports($content, array $options = [])
    {
        return $content instanceof Post;
    }

    /**
     * @param mixed $content
     *
     * @return bool
     */
    public function visible($content)
    {
        return $content instanceof Post;
    }
}

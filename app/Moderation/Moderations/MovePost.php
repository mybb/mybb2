<?php

namespace MyBB\Core\Moderation\Moderations;

use McCool\LaravelAutoPresenter\HasPresenter;
use MyBB\Core\Database\Models\Post;
use MyBB\Core\Database\Models\Topic;
use MyBB\Core\Database\Repositories\TopicRepositoryInterface;
use MyBB\Core\Moderation\ModerationInterface;

class MovePost implements ModerationInterface, HasPresenter
{
    /**
     * @var TopicRepositoryInterface
     */
    protected $topicRepository;

    /**
     * @param TopicRepositoryInterface $topicRepository
     */
    public function __construct(TopicRepositoryInterface $topicRepository)
    {
        $this->topicRepository = $topicRepository;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return 'move_post';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Move';
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return 'fa-arrow-right';
    }

    /**
     * @param Post $post
     * @param Topic $topic
     */
    public function move(Post $post, Topic $topic)
    {
        $this->topicRepository->movePostToTopic($post, $topic);
    }

    /**
     * @param mixed $content
     * @param array $options
     *
     * @return mixed
     */
    public function apply($content, array $options = [])
    {
        $topic = $this->topicRepository->find($options['topic_id']);
        $this->move($content, $topic);
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
     * Get the presenter class.
     *
     * @return string
     */
    public function getPresenterClass()
    {
        return 'MyBB\Core\Presenters\Moderations\MovePostPresenter';
    }
}

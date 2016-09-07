<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Moderation\Moderations;

use McCool\LaravelAutoPresenter\HasPresenter;
use MyBB\Core\Database\Models\Post;
use MyBB\Core\Database\Models\Topic;
use MyBB\Core\Database\Repositories\TopicRepositoryInterface;
use MyBB\Core\Moderation\DestinedInterface;
use MyBB\Core\Moderation\ModerationInterface;
use MyBB\Core\Moderation\SourceableInterface;

class MovePost implements ModerationInterface, HasPresenter, DestinedInterface, SourceableInterface
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
        return 'moderation.moderate.move';
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
        if ($this->supports($content, $options)) {
            $topic = $this->topicRepository->find($options['topic_id']);
            $this->move($content, $topic);
        }
    }

    /**
     * @param mixed $content
     * @param array $options
     *
     * @return bool
     */
    public function supports($content, array $options = [])
    {
        return $content instanceof Post && array_key_exists('topic_id', $options);
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

    /**
     * @param mixed $content
     *
     * @return bool
     */
    public function visible($content)
    {
        return $content instanceof Post;
    }

    /**
     * @return string
     */
    public function getPermissionName()
    {
        return 'canMovePosts';
    }

    /**
     * @return string
     */
    public function getDestinationType()
    {
        return 'topic';
    }

    /**
     * @return string
     */
    public function getDestinationKey()
    {
        return 'topic_id';
    }
}

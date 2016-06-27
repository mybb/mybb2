<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Moderation\Moderations;

use McCool\LaravelAutoPresenter\HasPresenter;
use MyBB\Core\Database\Models\Topic;
use MyBB\Core\Moderation\ModerationInterface;
use MyBB\Core\Services\TopicDeleter;

class DeleteTopic implements ModerationInterface, HasPresenter
{
    /**
     * @var TopicDeleter
     */
    protected $topicDeleter;

    /**
     * @param TopicDeleter $topicDeleter
     */
    public function __construct(TopicDeleter $topicDeleter)
    {
        $this->topicDeleter = $topicDeleter;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return 'delete_topic';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Delete';
    }

    /**
     * @param Topic $topic
     */
    public function deleteTopic(Topic $topic)
    {
        $this->topicDeleter->deleteTopic($topic);
    }

    /**
     * @param mixed $content
     * @param array $options
     *
     * @return mixed
     */
    public function apply($content, array $options = [])
    {
        if ($this->supports($content)) {
            $this->deleteTopic($content);
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
        return $content instanceof Topic;
    }

    /**
     * @param mixed $content
     *
     * @return bool
     */
    public function visible($content)
    {
        return $content instanceof Topic;
    }

    /**
     * Get the presenter class.
     *
     * @return string
     */
    public function getPresenterClass()
    {
        return 'MyBB\Core\Presenters\Moderations\DeleteTopicPresenter';
    }

    /**
     * @return string
     */
    public function getPermissionName()
    {
        return 'canDeleteTopics';
    }
}

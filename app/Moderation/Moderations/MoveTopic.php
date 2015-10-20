<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Moderation\Moderations;

use McCool\LaravelAutoPresenter\HasPresenter;
use MyBB\Core\Database\Models\Forum;
use MyBB\Core\Database\Models\Topic;
use MyBB\Core\Database\Repositories\ForumRepositoryInterface;
use MyBB\Core\Moderation\DestinedInterface;
use MyBB\Core\Moderation\ModerationInterface;
use MyBB\Core\Moderation\SourceableInterface;
use MyBB\Core\Presenters\Moderations\MoveTopicPresenter;

class MoveTopic implements ModerationInterface, HasPresenter, DestinedInterface, SourceableInterface
{
	/**
	 * @var ForumRepositoryInterface
	 */
	protected $forumRepository;

	/**
	 * @param ForumRepositoryInterface $forumRepository
	 */
	public function __construct(ForumRepositoryInterface $forumRepository)
	{
		$this->forumRepository = $forumRepository;
	}

	/**
	 * @return string
	 */
	public function getKey()
	{
		return 'move_topic';
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'Move';
	}

	/**
	 * @param Topic $topic
	 * @param Forum $forum
	 */
	public function moveTopic(Topic $topic, Forum $forum)
	{
		$this->forumRepository->moveTopicToForum($topic, $forum);
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
			$forum = $this->forumRepository->find($options['forum_id']);
			$this->moveTopic($content, $forum);
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
		return $content instanceof Topic && array_key_exists('forum_id', $options);
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
		return MoveTopicPresenter::class;
	}

	/**
	 * @return string
	 */
	public function getPermissionName()
	{
		return 'canMoveTopics';
	}

	/**
	 * @return string
	 */
	public function getDestinationType()
	{
		return 'forum';
	}

	/**
	 * @return string
	 */
	public function getDestinationKey()
	{
		return 'forum_id';
	}
}

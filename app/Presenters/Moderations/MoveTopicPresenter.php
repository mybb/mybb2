<?php

namespace MyBB\Core\Presenters\Moderations;

use McCool\LaravelAutoPresenter\BasePresenter;
use MyBB\Core\Database\Repositories\ForumRepositoryInterface;
use MyBB\Core\Form\Field;
use MyBB\Core\Form\RenderableInterface;
use MyBB\Core\Moderation\Moderations\MoveTopic;

class MoveTopicPresenter extends BasePresenter implements ModerationPresenterInterface
{
	/**
	 * @var ForumRepositoryInterface
	 */
	protected $forumRepository;

	/**
	 * @param MoveTopic                $resource
	 * @param ForumRepositoryInterface $forumRepository
	 */
	public function __construct(MoveTopic $resource, ForumRepositoryInterface $forumRepository)
	{
		parent::__construct($resource);
		$this->forumRepository = $forumRepository;
	}

	/**
	 * @return MoveTopic
	 */
	public function getWrappedObject()
	{
		return parent::getWrappedObject();
	}

	/**
	 * @return string
	 */
	public function key()
	{
		return $this->getWrappedObject()->getKey();
	}

	/**
	 * @return string
	 */
	public function icon()
	{
		return 'fa-arrow-right';
	}

	/**
	 * @return string
	 */
	public function name()
	{
		return $this->getWrappedObject()->getName();
	}

	/**
	 * @return RenderableInterface[]
	 */
	public function fields()
	{
		$forums = $this->forumRepository->all();
		$options = [];

		foreach ($forums as $forum) {
			$options[$forum->id] = $forum->title;
		}

		return [
			(new Field(
				'select',
				'forum_id',
				trans('moderation.move_topic_forum_id_name'),
				trans('moderation.move_topic_forum_id_description')
			))->setOptions($options),
		];
	}
}

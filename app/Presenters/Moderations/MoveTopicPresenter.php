<?php

namespace MyBB\Core\Presenters\Moderations;

use McCool\LaravelAutoPresenter\BasePresenter;
use MyBB\Core\Form\Field;
use MyBB\Core\Form\RenderableInterface;
use MyBB\Core\Moderation\Moderations\MoveTopic;

class MoveTopicPresenter extends BasePresenter implements ModerationPresenterInterface
{
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
		return $this->getWrappedObject()->getIcon();
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
		$forums = app()->make('MyBB\Core\Database\Repositories\ForumRepositoryInterface')->all();
		$options = [];

		foreach ($forums as $forum) {
			$options[$forum->id] = $forum->title;
		}

		return [
			(new Field(
				'select',
				'forum_id',
				'Forum',
				'The forum to move these posts to.'
			))->setOptions($options),
		];
	}
}

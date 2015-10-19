<?php

namespace MyBB\Core\Presenters\Moderations;

use Illuminate\View\Factory;
use MyBB\Core\Database\Repositories\ForumRepositoryInterface;
use MyBB\Core\Form\Field;
use MyBB\Core\Form\RenderableInterface;
use MyBB\Core\Moderation\Moderations\MoveTopic;

class MoveTopicPresenter extends AbstractModerationPresenter implements ModerationPresenterInterface
{
	/**
	 * @var ForumRepositoryInterface
	 */
	protected $forumRepository;

	/**
	 * @param MoveTopic                $resource
	 * @param ForumRepositoryInterface $forumRepository
	 * @param Factory                  $viewFactory
	 */
	public function __construct(MoveTopic $resource, ForumRepositoryInterface $forumRepository, Factory $viewFactory)
	{
		parent::__construct($resource, $viewFactory);
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
	public function icon()
	{
		return 'fa-arrow-right';
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

	/**
	 * @return string
	 */
	protected function getDescriptionView()
	{
		return 'partials.moderation.logs.move';
	}
}

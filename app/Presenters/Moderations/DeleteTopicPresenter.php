<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Presenters\Moderations;

use McCool\LaravelAutoPresenter\BasePresenter;
use MyBB\Core\Form\RenderableInterface;
use MyBB\Core\Moderation\Moderations\DeleteTopic;

class DeleteTopicPresenter extends BasePresenter implements ModerationPresenterInterface
{
	/**
	 * @return DeleteTopic
	 */
	public function getWrappedObject()
	{
		return parent::getWrappedObject();
	}

	/**
	 * @return RenderableInterface[]
	 */
	public function fields()
	{
		return [];
	}

	/**
	 * @return string
	 */
	public function icon()
	{
		return 'fa-trash-o';
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
	public function name()
	{
		return $this->getWrappedObject()->getName();
	}
}

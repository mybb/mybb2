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
use MyBB\Core\Moderation\Moderations\MergePosts;

class MergePostsPresenter extends BasePresenter implements ModerationPresenterInterface
{
	/**
	 * @return MergePosts
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
		return 'fa-code-fork';
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

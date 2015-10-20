<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Moderation\Moderations;

use McCool\LaravelAutoPresenter\HasPresenter;
use MyBB\Core\Moderation\ReversibleModerationInterface;
use MyBB\Core\Moderation\SourceableInterface;
use MyBB\Core\Presenters\Moderations\ClosePresenter;

class Close implements ReversibleModerationInterface, HasPresenter, SourceableInterface
{
	/**
	 * @return string
	 */
	public function getKey()
	{
		return 'close';
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'Close';
	}

	/**
	 * @param CloseableInterface $closeable
	 *
	 * @return mixed
	 */
	public function close(CloseableInterface $closeable)
	{
		return $closeable->close();
	}

	/**
	 * @param CloseableInterface $closeable
	 *
	 * @return mixed
	 */
	public function open(CloseableInterface $closeable)
	{
		return $closeable->open();
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
			return $this->close($content);
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
		return $content instanceof CloseableInterface;
	}

	/**
	 * @param mixed $content
	 *
	 * @return bool
	 */
	public function visible($content)
	{
		return $content instanceof CloseableInterface;
	}

	/**
	 * @param mixed $content
	 * @param array $options
	 *
	 * @return mixed
	 */
	public function reverse($content, array $options = [])
	{
		if ($this->supports($content)) {
			return $this->open($content);
		}
	}

	/**
	 * @return string
	 */
	public function getReverseName()
	{
		return 'Open';
	}

	/**
	 * Get the presenter class.
	 *
	 * @return string
	 */
	public function getPresenterClass()
	{
		return ClosePresenter::class;
	}

	/**
	 * @return string
	 */
	public function getPermissionName()
	{
		return 'canClose';
	}
}

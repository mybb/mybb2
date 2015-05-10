<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Moderation\Moderations;

use MyBB\Core\Moderation\ReversibleModerationInterface;

class Approve implements ReversibleModerationInterface
{
	/**
	 * @param ApprovableInterface $approvable
	 *
	 * @return mixed
	 */
	public function approve(ApprovableInterface $approvable)
	{
		return $approvable->approve();
	}

	/**
	 * @param ApprovableInterface $approvable
	 *
	 * @return mixed
	 */
	public function unapprove(ApprovableInterface $approvable)
	{
		return $approvable->unapprove();
	}

	/**
	 * @return string
	 */
	public function getKey()
	{
		return 'approve';
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'Approve';
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
			return $this->approve($content);
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
		return $content instanceof ApprovableInterface;
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
			return $this->unapprove($content);
		}
	}

	/**
	 * @return string
	 */
	public function getReverseName()
	{
		return 'Unapprove';
	}

	/**
	 * @return string
	 */
	public function getIcon()
	{
		return 'fa-check';
	}

	/**
	 * @return string
	 */
	public function getReverseIcon()
	{
		return 'fa-minus';
	}

	/**
	 * @param mixed $content
	 *
	 * @return bool
	 */
	public function visible($content)
	{
		return $content instanceof ApprovableInterface;
	}
}

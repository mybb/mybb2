<?php

namespace MyBB\Core\Moderation\Moderations;

use MyBB\Core\Moderation\ReversibleModerationInterface;

class Close implements ReversibleModerationInterface
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
	 * @return string
	 */
	public function getIcon()
	{
		return 'fa-lock';
	}

	/**
	 * @param CloseableInterface $closeable
	 */
	public function close(CloseableInterface $closeable)
	{
		$closeable->close();
	}

	/**
	 * @param CloseableInterface $closeable
	 */
	public function open(CloseableInterface $closeable)
	{
		$closeable->open();
	}

	/**
	 * @param mixed $content
	 * @param array $options
	 *
	 * @return mixed
	 */
	public function apply($content, array $options = [])
	{
		$this->close($content);
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
		$this->open($content);
	}

	/**
	 * @return string
	 */
	public function getReverseName()
	{
		return 'Open';
	}

	/**
	 * @return string
	 */
	public function getReverseIcon()
	{
		return 'fa-unlock';
	}
}

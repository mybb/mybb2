<?php

namespace MyBB\Core\Moderation;

interface ReversibleModerationInterface extends ModerationInterface
{
	/**
	 * @param mixed $content
	 * @param array $options
	 *
	 * @return mixed
	 */
	public function reverse($content, array $options = []);

	/**
	 * @return string
	 */
	public function getReverseName();

	/**
	 * @return string
	 */
	public function getReverseIcon();
}

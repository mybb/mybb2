<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

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
}

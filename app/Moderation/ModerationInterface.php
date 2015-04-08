<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Moderation;

interface ModerationInterface
{
	/**
	 * @return string
	 */
	public function getKey();

	/**
	 * @return string
	 */
	public function getName();

	/**
	 * @param mixed $content
	 * @param array $options
	 *
	 * @return mixed
	 */
	public function apply($content, array $options = []);

	/**
	 * @param mixed $content
	 * @param array $options
	 *
	 * @return bool
	 */
	public function supports($content, array $options = []);

	/**
	 * @param mixed $content
	 *
	 * @return bool
	 */
	public function visible($content);
}

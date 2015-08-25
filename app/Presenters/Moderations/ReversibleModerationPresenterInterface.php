<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Presenters\Moderations;

interface ReversibleModerationPresenterInterface extends ModerationPresenterInterface
{
	/**
	 * @return string
	 */
	public function reverseIcon();

	/**
	 * @return string
	 */
	public function reverseName();
}

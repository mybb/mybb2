<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Presenters\Moderations;

use MyBB\Core\Content\ContentInterface;

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

	/**
	 * @param array            $contentCollection
	 * @param ContentInterface $source
	 * @param ContentInterface $destination
	 *
	 * @return string
	 */
	public function reverseDescribe(
		array $contentCollection,
		ContentInterface $source = null,
		ContentInterface $destination = null
	);
}

<?php
/**
 * Contract for items to store user activity history for.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2014, MyBB Group
 * @license   http://www.mybb.com/about/license GNU LESSER GENERAL PUBLIC LICENSE
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\UserActivity\Contracts;

interface ActivityStoreableInterface
{
	/**
	 * Check whether this activity entry should be saved.
	 *
	 * @return bool
	 */
	public function checkStoreable();

	/**
	 * Get the ID of the model.
	 *
	 * @return int
	 */
	public function getContentId();

	/**
	 * Get extra details about a model.
	 *
	 * @return array The extra details to store.
	 */
	public function getExtraDetails();
}

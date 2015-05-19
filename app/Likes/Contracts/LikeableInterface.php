<?php
/**
 * Contract that content that can be liked should adhere to.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2014, MyBB Group
 * @license   http://www.mybb.com/about/license GNU LESSER GENERAL PUBLIC LICENSE
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\Likes\Contracts;

interface LikeableInterface
{
	/**
	 * Get the short name of the content being liked.
	 *
	 * For example: "post".
	 *
	 * @return string
	 */
	public function getContentTypeShortName();

	/**
	 * Get the title of the content being liked.
	 *
	 * @return string
	 */
	public function getContentTitle();

	/**
	 * Get the author of the content being liked.
	 *
	 * @return \MyBB\Core\Database\Models\User
	 */
	public function getContentAuthor();

	/**
	 * Get the URL to view this content.
	 *
	 * @return string
	 */
	public function getViewUrl();
}

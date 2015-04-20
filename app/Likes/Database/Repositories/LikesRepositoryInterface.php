<?php
/**
 * Repository contract for managing "liked" content.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2014, MyBB Group
 * @license   http://www.mybb.com/about/license GNU LESSER GENERAL PUBLIC LICENSE
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\Likes\Database\Repositories;

use Illuminate\Database\Eloquent\Model;
use MyBB\Core\Likes\Traits\LikeableTrait;

interface LikesRepositoryInterface
{
	/**
	 * Get all of the likes created by a user, paginated.
	 *
	 * @param int|\MyBB\Core\Database\Models\User    $user The user to retrieve the likes for.
	 * @param int $perPage The number of likes per page.
	 *
	 * @return mixed
	 */
	public function getAllLikesByUserPaginated($user, $perPage = 20);

	/**
	 * Retrieve all of the likes a piece of content has received.
	 *
	 * @param \Illuminate\Database\Eloquent\Model|LikeableTrait $content The content to retrieve the likes for.
	 *
	 * @param int                                               $perPage The number of likes to show per page.
	 *
	 * @return mixed
	 */
	public function getAllLikesForContentPaginated(Model $content, $perPage = 10);

	/**
	 * Get all of the likes for a set of entries of a specific content type.
	 *
	 * @param Model $contentType The type of the content to get all of the likes for.
	 * @param array $ids An array of IDs of the entries to get the likes for.
	 *
	 * @return mixed
	 */
	public function getAllLikesForContents(Model $contentType, array $ids);

	/**
	 * Toggle a like on or off for a given piece of content for the current user.
	 *
	 * @param \Illuminate\Database\Eloquent\Model|LikeableTrait $content The content to toggle the like for.
	 *
	 * @return mixed
	 */
	public function toggleLikeForContent(Model $content);
}

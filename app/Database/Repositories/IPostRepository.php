<?php
/**
 * Post repository contract.
 *
 * @version 1.0.0
 * @author MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Repositories;

use MyBB\Core\Database\Models\Topic;

interface IPostRepository
{
    /**
     * Find all posts created by a user.
     *
     * @param int $userId The ID of the user to get the posts for.
     *
     * @return mixed
     */
    public function allForUser($userId = 0);

    /**
     * Get all posts for a thread.
     *
     * @param Topic $topic The thread to fetch the posts for.
     *
     * @return mixed
     */
    public function allForTopic(Topic $topic);

    /**
     * Find a single post by its ID.
     *
     * @param int $id The ID of the post to find.
     *
     * @return mixed
     */
    public function find($id = 0);
}

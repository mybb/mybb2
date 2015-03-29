<?php
/**
 * Forum repository contract.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Repositories;

use MyBB\Core\Database\Models\Forum;
use MyBB\Core\Database\Models\Post;

interface IForumRepository
{
    /**
     * Get all forums.
     *
     * @return mixed
     */
    public function all();

    /**
     * Get the forum tree for the index, consisting of root forums (categories), and one level of descendants.
     *
     * @return mixed
     */
    public function getIndexTree();

    /**
     * Get a single forum by ID.
     *
     * @param int $id The ID of the forum.
     *
     * @return mixed
     */
    public function find($id = 0);

    /**
     * Get a single forum by slug (name, sluggified, eg: 'my-first-forum').
     *
     * @param string $slug The slug for the forum.
     *
     * @return mixed
     */
    public function findBySlug($slug = '');

    /**
     * Increment the number of posts in the forum by one.
     *
     * @param int $id The ID of the forum to increment the post count for.
     *
     * @return mixed
     */
    public function incrementPostCount($id = 0);

    /**
     * Increment the number of topics in the forum by one.
     *
     * @param int $id The ID of the forum to increment the topic count for.
     *
     * @return mixed
     */
    public function incrementTopicCount($id = 0);

    /**
     * Update the last post for this forum
     *
     * @param Forum $forum The forum to update
     *
     * @return mixed
     */

    public function updateLastPost(Forum $forum, Post $post = null);
}

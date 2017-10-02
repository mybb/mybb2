<?php
/**
 * Forum repository contract.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Repositories;

use Doctrine\Common\Collections\Collection;
use MyBB\Core\Database\Models\{
    Forum, Post, Topic
};

interface ForumRepositoryInterface
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
     * @param bool $checkPermissions
     *
     * @return mixed
     */
    public function getIndexTree(bool $checkPermissions = true);

    /**
     * Get a single forum by ID.
     *
     * @param int $id The ID of the forum.
     *
     * @return mixed
     */
    public function find(int $id = 0);

    /**
     * Get a single forum by slug (name, sluggified, eg: 'my-first-forum').
     *
     * @param string $slug The slug for the forum.
     *
     * @return mixed
     */
    public function findBySlug(string $slug = '');

    /**
     * Increment the number of posts in the forum by one.
     *
     * @param int $id The ID of the forum to increment the post count for.
     *
     * @return mixed
     */
    public function incrementPostCount(int $id = 0);

    /**
     * Increment the number of topics in the forum by one.
     *
     * @param int $id The ID of the forum to increment the topic count for.
     *
     * @return mixed
     */
    public function incrementTopicCount(int $id = 0);

    /**
     * Update the last post for this forum
     *
     * @param Forum $forum The forum to update
     * @param Post $post
     *
     * @return mixed
     */
    public function updateLastPost(Forum $forum, Post $post = null);

    /**
     * @param Topic $topic
     * @param Forum $forum
     */
    public function moveTopicToForum(Topic $topic, Forum $forum);

    /**
     * Create new forum
     *
     * @param array $details
     * @return mixed
     */
    public function create(array $details = []);

    /**
     * Return single forum by id (without any relations, just forum)
     *
     * @param int $id Forum id
     * @return Collection
     */
    public function getForum(int $id);

    /**
     * Check if there are already created any forums
     *
     * @return bool
     */
    public function isEmpty() : bool;

    /**
     * Delete forum by id. Removes all related forums/subforums/topics/posts
     *
     * @param Forum $forum
     * @return bool
     */
    public function delete(Forum $forum) : bool;

    /**
     * Update forum
     *
     * @param Forum $forum
     * @param $details
     * @return mixed
     */
    public function update(Forum $forum, $details);

    /**
     * @param Forum $forum
     * @param int $newParent
     * @return mixed
     */
    public function changeParent(Forum $forum, int $newParent);

    /**
     * Get only children forums
     *
     * @return mixed
     */
    public function onlyChildren();
}

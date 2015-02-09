<?php
/**
 * Thread repository implementation, using Eloquent ORM.
 *
 * @version 1.0.0
 * @author MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Repositories\Eloquent;

use MyBB\Core\Database\Models\Forum;
use MyBB\Core\Database\Models\Topic;
use MyBB\Core\Database\Repositories\ITopicRepository;

class TopicRepository implements ITopicRepository
{
    /**
     * @var Topic $topicModel
     * @access protected
     */
    protected $topicModel;

    /**
     * @param Topic $topicModel The model to use for threads.
     */
    public function __construct(Topic $topicModel) // TODO: Inject permissions container? So we can check thread permissions before querying?
    {
        $this->topicModel = $topicModel;
    }

    /**
     * Get all threads.
     *
     * @return mixed
     */
    public function all()
    {
        return $this->topicModel->all();
    }

    /**
     * Get all threads created by a user.
     *
     * @param int $userId The ID of the user.
     *
     * @return mixed
     */
    public function allForUser($userId = 0)
    {
        return $this->topicModel->where('user_id', '=', $userId)->get();
    }

    /**
     * Find a single thread by ID.
     *
     * @param int $id The ID of the thread to find.
     *
     * @return mixed
     */
    public function find($id = 0)
    {
        return $this->topicModel->find($id);
    }

    /**
     * Find a single thread by its slug.
     *
     * @param string $slug The slug of the thread. Eg: 'my-first-thread'.
     *
     * @return mixed
     */
    public function findBySlug($slug = '')
    {
        return $this->topicModel->where('slug', '=', $slug)->first();
    }

    /**
     * Get all threads within a forum.
     *
     * @param Forum $forum The forum the threads belong to.
     *
     * @return mixed
     */
    public function allForForum(Forum $forum)
    {
        return $this->topicModel->where('forum_id', '=', $forum->id)->get();
    }
}

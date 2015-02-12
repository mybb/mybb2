<?php
/**
 * Post repository implementation, using Eloquent ORM.
 *
 * @version 2.0.0
 * @author MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Repositories\Eloquent;

use MyBB\Core\Database\Models\Post;
use MyBB\Core\Database\Models\Topic;
use MyBB\Core\Database\Repositories\IPostRepository;

class PostRepository implements IPostRepository
{
    /**
     * @var Post $postModel
     * @access protected
     */
    protected $postModel;

    /**
     * @param Post $postModel The model to use for posts.
     */
    public function __construct(Post $postModel) // TODO: Inject permissions container? So we can check post permissions before querying?
    {
        $this->postModel = $postModel;
    }

    /**
     * Find all posts created by a user.
     *
     * @param int $userId The ID of the user to get the posts for.
     *
     * @return mixed
     */
    public function allForUser($userId = 0)
    {
        return $this->postModel->where('user_id', '=', $userId)->get();
    }

    /**
     * Find a single post by its ID.
     *
     * @param int $id The ID of the post to find.
     *
     * @return mixed
     */
    public function find($id = 0)
    {
        return $this->postModel->find($id);
    }

    /**
     * Get all posts for a thread.
     *
     * @param Topic $topic The thread to fetch the posts for.
     *
     * @return mixed
     */
    public function allForTopic(Topic $topic)
    {
        return $this->postModel->with(['author'])->where('topic_id', '=', $topic->id)->get();
    }

    /**
     * Add a post to a topic.
     *
     * @param Topic $topic       The topic to add a post to.
     * @param array $postDetails The details of the post to add.
     *
     * @return mixed
     */
    public function addPostToTopic(Topic $topic, array $postDetails)
    {
        $basePostDetails = [
            'user_id' => 0,
            'content' => '',
            'content_parsed' => '', // TODO: Auto-populate parsed content with parser once parser is written.
        ];

        $postDetails = array_merge($basePostDetails, $postDetails);

        return $topic->posts()->save(new Post($postDetails));
    }
}

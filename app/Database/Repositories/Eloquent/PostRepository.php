<?php
/**
 * Post repository implementation, using Eloquent ORM.
 *
 * @version 2.0.0
 * @author MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Repositories\Eloquent;

use Illuminate\Contracts\Auth\Guard;
use MyBB\Core\Database\Models\Post;
use MyBB\Core\Database\Models\Topic;
use MyBB\Core\Database\Repositories\IPostRepository;
use MyBB\Parser\MessageFormatter;

class PostRepository implements IPostRepository
{
    /**
     * @var Post $postModel
     * @access protected
     */
    protected $postModel;
    /**
     * @var Guard $guard
     * @access protected
     */
    protected $guard;
    /**
     * @var MessageFormatter $formatter
     * @access protected
     */
    protected $formatter;

    /**
     * @param Post             $postModel The model to use for posts.
     * @param Guard            $guard     Laravel guard instance, used to get user ID.
     * @param MessageFormatter $formatter Post formatter instance.
     */
    public function __construct(Post $postModel, Guard $guard, MessageFormatter $formatter) // TODO: Inject permissions container? So we can check post permissions before querying?
    {
        $this->postModel = $postModel;
        $this->guard = $guard;
        $this->formatter = $formatter;
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

    public function getNewest($num = 20)
    {
        return $this->postModel->orderBy('created_at', 'desc')->with(['topic', 'topic.forum', 'author'])->take($num)->get();
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
        return $this->postModel->with(['author'])->where('topic_id', '=', $topic->id)->paginate(10);
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

        $postDetails = array_merge([
                                       'user_id' => $this->guard->user()->id,
                                       'content' => '',
                                       'content_parsed' => '', // TODO: Auto-populate parsed content with parser once parser is written.
                                   ], $postDetails);

        $postDetails['content_parsed'] = $this->formatter->parse($postDetails['content'], [
            MessageFormatter::ME_USERNAME => $this->guard->user()->name,
        ]); // TODO: Parser options...

        $post = $topic->posts()->save(new Post($postDetails));

        if ($post !== false) {
            $topic->increment('num_posts');
            $topic->forum->increment('num_posts');
			$topic->update([
				'last_post_id' => $post['id']
			]);
        }

        return $post;
    }
	
    /**
     * Edit a post
     *
     * @param Post $post       The post to edit
     * @param array $postDetails The details of the post to add.
     *
     * @return mixed
     */
    public function editPost(Post $post, array $postDetails)
    {

        if($postDetails['content'])
		{
			$postDetails['content_parsed'] = $this->formatter->parse($postDetails['content'], [
                MessageFormatter::ME_USERNAME => $this->guard->user()->name,
            ]); // TODO: Parser options...
		}
		
		$post->update($postDetails);

        return $post;
    }

    /**
     * Delete posts of topic
     *
     * @param Topic $topic       The topic that you want to delete its posts
     *
     * @return mixed
     */

	public function deletePostsForTopic(Topic $topic)
	{
		return $this->postModel->where('topic_id', '=', $topic->id)->delete();
	}

    /**
     * Delete a post
     *
     * @param Post $post       The post to delete
     *
     * @return mixed
     */

	public function deletePost(Post $post)
	{
		$post->topic->decrement('num_posts');
		return $post->delete();
	}
}

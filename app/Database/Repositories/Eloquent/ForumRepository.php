<?php
/**
 * Forum repository implementation, using Eloquent ORM.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Repositories\Eloquent;

use Illuminate\Database\DatabaseManager;
use MyBB\Core\Database\Models\{
    Forum, Post, Topic
};
use MyBB\Core\Database\Repositories\ForumRepositoryInterface;
use MyBB\Core\Permissions\PermissionChecker;

class ForumRepository implements ForumRepositoryInterface
{
    /**
     * @var Forum $forumModel
     */
    protected $forumModel;

    /**
     * @var PermissionChecker
     */
    private $permissionChecker;

    /**
     * @var DatabaseManager
     */
    protected $dbManager;

    /**
     * @param Forum $forumModel                    The model to use for forums.
     * @param PermissionChecker $permissionChecker The permission class
     * @param DatabaseManager $dbManager
     */
    public function __construct(
        Forum $forumModel,
        PermissionChecker $permissionChecker,
        DatabaseManager $dbManager
    ) {
        $this->forumModel = $forumModel;
        $this->permissionChecker = $permissionChecker;
        $this->dbManager = $dbManager;
    }

    /**
     * Get all forums.
     *
     * @return mixed
     */
    public function all()
    {
        return $this->forumModel->all();
    }


    /**
     * Find a single forum by ID.
     *
     * @param int $id The ID of the forum to find.
     *
     * @return mixed
     */
    public function find(int $id = 0)
    {
        $unviewable = $this->permissionChecker->getUnviewableIdsForContent('forum');

        $parent = $this->forumModel
            ->select('left_id', 'right_id')
            ->whereNotIn('id', $unviewable)
            ->find($id);

        if (!$parent) {
            return $parent;
        }

        $forums = $this->forumModel
            ->with(['lastPost', 'lastPost.topic', 'lastPostAuthor'])
            ->whereNotIn('id', $unviewable)
            ->whereBetween('left_id', [$parent->left_id, $parent->right_id])
            ->get();

        return $forums->toTree()->first();
    }

    /**
     * Find a single forum by its slug.
     *
     * @param string $slug The slug of the forum. Eg: 'my-first-forum'.
     *
     * @return mixed
     */
    public function findBySlug(string $slug = '')
    {
        $unviewable = $this->permissionChecker->getUnviewableIdsForContent('forum');

        $parent = $this->forumModel
            ->select('left_id', 'right_id')
            ->whereNotIn('id', $unviewable)
            ->whereSlug($slug)
            ->first();

        if (!$parent) {
            return $parent;
        }

        $forums = $this->forumModel
            ->with(['lastPost', 'lastPost.topic', 'lastPostAuthor'])
            ->whereNotIn('id', $unviewable)
            ->whereBetween('left_id', [$parent->left_id, $parent->right_id])
            ->get();

        return $forums->toTree()->first();
    }

    /**
     * Get the forum tree for the index, consisting of root forums (categories), and one level of descendants.
     *
     * @param bool $checkPermissions
     *
     * @return mixed
     */
    public function getIndexTree(bool $checkPermissions = true)
    {
        // TODO: The caching decorator would also cache the relations here
        $baseQuery = $this->forumModel;

        if ($checkPermissions) {
            $unviewable = $this->permissionChecker->getUnviewableIdsForContent('forum');
            $baseQuery = $baseQuery->whereNotIn('id', $unviewable);
        }

        $res = $baseQuery->with([
            'lastPost',
            'lastPost.topic',
            'lastPostAuthor',
        ])->get();
        return $res->toTree();
    }

    /**
     * {@inheritdoc}
     */
    public function getForum(int $id)
    {
        return $this->forumModel->find($id);
    }

    /**
     * Increment the number of posts in the forum by one.
     *
     * @param int $id The ID of the forum to increment the post count for.
     *
     * @return mixed
     */
    public function incrementPostCount(int $id = 0)
    {
        $forum = $this->forumModel->find($id);

        if ($forum) {
            $forum->increment('num_posts');
        }

        return $forum;
    }

    /**
     * Increment the number of topics in the forum by one.
     *
     * @param int $id The ID of the forum to increment the topic count for.
     *
     * @return mixed
     */
    public function incrementTopicCount(int $id = 0)
    {
        $forum = $this->forumModel->find($id);

        if ($forum) {
            $forum->increment('num_topics');
        }

        return $forum;
    }

    /**
     * Update the last post for this forum
     *
     * @param Forum $forum The forum to update
     * @param Post $post
     *
     * @return mixed
     */
    public function updateLastPost(Forum $forum, Post $post = null)
    {
        if ($post === null) {
            $topic = $forum->topics->sortByDesc('last_post_id')->first();
            if ($topic != null) {
                $post = $topic->lastPost;
            }
        }

        if ($post != null) {
            $forum->update([
                'last_post_id'      => $post->id,
                'last_post_user_id' => $post->user_id,
            ]);
        } else {
            $forum->update([
                'last_post_id'      => null,
                'last_post_user_id' => null,
            ]);
        }

        return $forum;
    }

    /**
     * @param Topic $topic
     * @param Forum $forum
     */
    public function moveTopicToForum(Topic $topic, Forum $forum)
    {
        $topic->forum->decrement('num_topics');
        $topic->forum->decrement('num_posts', $topic->num_posts);

        $topic->forum_id = $forum->id;
        $topic->save();

        $topic->forum->increment('num_topics');
        $topic->forum->increment('num_posts', $topic->num_posts);

        $this->updateLastPost($forum);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $details = [])
    {
        return $this->dbManager->transaction(function () use ($details) {
            $this->forumModel->where('left_id', '>=', $details['left_id'])->increment('left_id', 2);
            $this->forumModel->where('right_id', '>=', $details['left_id'])->increment('right_id', 2);
            $this->forumModel->create($details);
        }, 2);
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty() : bool
    {
        $result = $this->forumModel->select('id')->first();
        return !(bool)$result;
    }

    /**
     * {@inheritdoc}
     */
    public function update(Forum $forum, $details)
    {
        return $forum->update($details);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Forum $forum) : bool
    {
        //TODO implement function
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function changeParent(Forum $forum, int $newParent)
    {
        //TODO implement function
    }

    /**
     * {@inheritdoc}
     */
    public function onlyChildren()
    {
        return $this->forumModel->where('parent_id', '!=', null)->get();
    }
}

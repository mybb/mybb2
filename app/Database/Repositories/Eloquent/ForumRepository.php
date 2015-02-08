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
use MyBB\Core\Database\Models\Thread;
use MyBB\Core\Database\Repositories\IForumRepository;


class ForumRepository implements IForumRepository
{
    /**
     * @var Forum $forumModel
     * @access protected
     */
    protected $forumModel;

    /**
     * @param Forum $forumModel The model to use for forums.
     */
    public function __construct(Forum $forumModel) // TODO: Inject permissions container? So we can check thread permissions before querying?
    {
        $this->forumModel = $forumModel;
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
    public function find($id = 0)
    {
        return $this->forumModel->find($id);
    }

    /**
     * Find a single forum by its slug.
     *
     * @param string $slug The slug of the forum. Eg: 'my-first-forum'.
     *
     * @return mixed
     */
    public function findBySlug($slug = '')
    {
        return $this->forumModel->whereSlug($slug)->with(['children'])->first();
    }

    /**
     * Get the forum tree for the index, consisting of root forums (categories), and one level of descendants.
     *
     * @return mixed
     */
    public function getIndexTree()
    {
        return $this->forumModel->where('parent_id', '=', null)->with(['children'])->get();
    }
}

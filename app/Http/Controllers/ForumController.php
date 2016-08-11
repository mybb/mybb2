<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Controllers;

use DaveJamesMiller\Breadcrumbs\Manager as Breadcrumbs;
use Illuminate\Http\Request;
use MyBB\Core\Database\Repositories\ForumRepositoryInterface;
use MyBB\Core\Database\Repositories\PostRepositoryInterface;
use MyBB\Core\Database\Repositories\TopicRepositoryInterface;
use MyBB\Core\Database\Repositories\UserRepositoryInterface;
use MyBB\Core\Exceptions\ForumNotFoundException;
use MyBB\Settings\Store;

class ForumController extends AbstractController
{
    /**
     * @var ForumRepositoryInterface
     */
    private $forumRepository;

    /**
     * @var TopicRepositoryInterface
     */
    private $topicRepository;

    /**
     * @var PostRepositoryInterface
     */
    private $postRepository;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var Breadcrumbs
     */
    private $breadcrumbs;

    /**
     * Create a new controller instance.
     *
     * @param ForumRepositoryInterface $forumRepository Forum repository instance to use in order to load forum
     *                                                  information.
     * @param PostRepositoryInterface $postRepository Post repository instance to use in order to load posts for the
     *                                                  latest discussion table.
     * @param TopicRepositoryInterface $topicRepository Thread repository instance to use in order to load threads
     *                                                  within a forum.
     * @param UserRepositoryInterface $userRepository
     * @param Breadcrumbs $breadcrumbs
     */
    public function __construct(
        ForumRepositoryInterface $forumRepository,
        PostRepositoryInterface $postRepository,
        TopicRepositoryInterface $topicRepository,
        UserRepositoryInterface $userRepository,
        Breadcrumbs $breadcrumbs
    ) {
        $this->forumRepository = $forumRepository;
        $this->topicRepository = $topicRepository;
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
        $this->breadcrumbs = $breadcrumbs;
    }

    /**
     * Shows all Forums
     *
     * @return \Illuminate\View\View
     */
    public function all()
    {
        // Forum permissions are checked in "getIndexTree"
        $forums = $this->forumRepository->getIndexTree();

        return view('forum.all', compact('forums'));
    }

    /**
     * Shows the Index Page
     *
     * @param Store $settings
     *
     * @return \Illuminate\View\View
     */
    public function index(Store $settings)
    {
        // Forum permissions are checked in "getIndexTree" and "getNewest"
        $topics = $this->topicRepository->getNewest();

        return view('forum.index', compact('topics'));
    }

    /**
     * Shows a specific forum.
     *
     * @param Request $request
     * @param int $id The ID of the forum to show.
     * @param string $slug The slug of the forum to show.
     *
     * @return \Illuminate\View\View
     */
    public function show(Request $request, $id, $slug)
    {
        // Forum permissions are checked in "find"
        $forum = $this->forumRepository->find($id);

        // Load last post information for child forums
        $forum->load(['children.lastPost', 'children.lastPost.topic', 'children.lastPostAuthor']);

        if (!$forum) {
            throw new ForumNotFoundException;
        }

        $this->breadcrumbs->setCurrentRoute('forums.show', $forum);

        // Build the order by/dir parts
        $allowed = ['lastpost', 'replies', 'startdate', 'title'];

        $orderBy = $request->get('orderBy', 'lastpost');
        if (!in_array($orderBy, $allowed)) {
            $orderBy = 'lastpost';
        }

        $orderDir = $request->get('orderDir', 'desc');
        if ($orderDir != 'asc' && $orderDir != 'desc') {
            $orderDir = 'desc';
        }

        // We need to know how to build the url...
        $urlDirs = [
            'lastpost'  => 'desc',
            'replies'   => 'desc',
            'startdate' => 'desc',
            'title'     => 'asc',
        ];
        if ($orderDir == 'desc' && $urlDirs[$orderBy] == 'desc') {
            $urlDirs[$orderBy] = 'asc';
        } elseif ($orderDir == 'asc' && $urlDirs[$orderBy] == 'asc') {
            $urlDirs[$orderBy] = 'desc';
        }

        $topics = $this->topicRepository->allForForum($forum, $orderBy, $orderDir);

        $topics->appends(['orderBy' => $orderBy, 'orderDir' => $orderDir]);

        return view('forum.show', compact('forum', 'topics', 'orderBy', 'orderDir', 'urlDirs'));
    }
}

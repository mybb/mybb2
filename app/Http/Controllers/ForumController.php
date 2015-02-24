<?php namespace MyBB\Core\Http\Controllers;

use Illuminate\Http\Request;
use MyBB\Core\Database\Repositories\IForumRepository;
use MyBB\Core\Database\Repositories\IPostRepository;
use MyBB\Core\Database\Repositories\ITopicRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ForumController extends Controller
{
	/** @var IForumRepository $forumRepository */
	private $forumRepository;
	/** @var ITopicRepository $topicRepository */
	private $topicRepository;
	/** @var IPostRepository $postRepository */
	private $postRepository;

	/**
	 * Create a new controller instance.
	 *
	 * @param IForumRepository $forumRepository Forum repository instance to use in order to load forum information.
	 * @param ITopicRepository $topicRepository Thread repository instance to use in order to load threads within a
	 *                                          forum.
	 * @param IPostRepository  $postRepository  Post repository instance to use in order to load posts for the latest
	 *                                          discussion table.
	 */
	public function __construct(
		IForumRepository $forumRepository,
		ITopicRepository $topicRepository,
		IPostRepository $postRepository
	) {
		$this->forumRepository = $forumRepository;
		$this->topicRepository = $topicRepository;
		$this->postRepository = $postRepository;
	}

	/**
	 * Shows all Forums
	 *
	 * @return \Illuminate\View\View
	 */
	public function all()
	{
		$forums = $this->forumRepository->getIndexTree();

		return view('forum.all', compact('forums'));
	}

	/**
	 * Shows the Index Page
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		$forums = $this->forumRepository->getIndexTree();
		$topics = $this->topicRepository->getNewest();

		return view('forum.index', compact('forums', 'topics'));
	}

	/**
	 * Shows a specific forum.
	 *
	 * @param string $slug The slug of the forum to show.
	 *
	 * @return \Illuminate\View\View
	 */
	public function show(Request $request, $slug = '')
	{
		$forum = $this->forumRepository->findBySlug($slug);

		if (!$forum) {
			throw new NotFoundHttpException(trans('errors.forum_not_found'));
		}

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
			'lastpost' => 'desc',
			'replies' => 'desc',
			'startdate' => 'desc',
			'title' => 'asc',
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

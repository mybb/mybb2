<?php namespace MyBB\Core\Http\Controllers;

use MyBB\Core\Database\Repositories\IForumRepository;
use MyBB\Core\Database\Repositories\ITopicRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ForumController extends Controller
{
	/** @var IForumRepository $forumRepository */
	private $forumRepository;
	/** @var ITopicRepository $topicRepository */
	private $topicRepository;

	/**
	 * Create a new controller instance.
	 *
	 * @param IForumRepository  $forumRepository Forum repository instance to use in order to load forum information.
	 * @param ITopicRepository $topicRepository Thread repository instance to use in order to load threads within a forum.
	 */
	public function __construct(IForumRepository $forumRepository, ITopicRepository $topicRepository)
	{
		$this->forumRepository  = $forumRepository;
		$this->topicRepository = $topicRepository;
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

		return view('forum.index', compact('forums'));
	}

	/**
	 * Shows a specific forum.
	 *
	 * @param string $slug The slug of the forum to show.
	 *
	 * @return \Illuminate\View\View
	 */
	public function show($slug = '')
	{
		$forum = $this->forumRepository->findBySlug($slug);

		if (!$forum) {
			throw new NotFoundHttpException('Forum not found.');
		}

		$topics = $this->topicRepository->allForForum($forum);

		return view('forum.show', compact('forum', 'topics'));
	}
}

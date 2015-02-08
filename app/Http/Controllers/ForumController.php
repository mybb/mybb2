<?php namespace MyBB\Core\Http\Controllers;

use MyBB\Core\Database\Repositories\IForumRepository;
use MyBB\Core\Database\Repositories\IThreadRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ForumController extends Controller
{
	/** @var IForumRepository $forumRepository */
	private $forumRepository;
	/** @var IThreadRepository $threadRepository */
	private $threadRepository;

	/**
	 * Create a new controller instance.
	 *
	 * @param IForumRepository  $forumRepository Forum repository instance to use in order to load forum information.
	 * @param IThreadRepository $threadRepository Thread repository instance to use in order to load threads within a forum.
	 */
	public function __construct(IForumRepository $forumRepository, IThreadRepository $threadRepository)
	{
		$this->forumRepository  = $forumRepository;
		$this->threadRepository = $threadRepository;
	}

	/**
	 * Shows the Index Page
	 *
	 * @return Response
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
	 */
	public function show($slug = '')
	{
		$forum = $this->forumRepository->findBySlug($slug);

		if (!$forum) {
			throw new NotFoundHttpException('Forum not found.');
		}

		$threads = $this->threadRepository->allForForum($forum);

		return view('forum.show', compact('forum', 'threads'));
	}
}

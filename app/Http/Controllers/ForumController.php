<?php

namespace MyBB\Core\Http\Controllers;

use Breadcrumbs;
use Illuminate\Auth\Guard;
use Illuminate\Http\Request;
use MyBB\Core\Database\Repositories\IForumRepository;
use MyBB\Core\Database\Repositories\IPostRepository;
use MyBB\Core\Database\Repositories\ITopicRepository;
use MyBB\Core\Database\Repositories\IUserRepository;
use MyBB\Settings\Store;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ForumController extends Controller
{
	/** @var IForumRepository $forumRepository */
	private $forumRepository;
	/** @var ITopicRepository $topicRepository */
	private $topicRepository;
	/** @var IPostRepository $postRepository */
	private $postRepository;
	/** @var  IUserRepository $userRepository */
	private $userRepository;

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
		IPostRepository $postRepository,
		IUserRepository $userRepository,
		Guard $guard,
		Request $request
	) {
		parent::__construct($guard, $request);

		$this->forumRepository = $forumRepository;
		$this->topicRepository = $topicRepository;
		$this->postRepository = $postRepository;
		$this->userRepository = $userRepository;
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
	public function index(Store $settings)
	{
		$forums = $this->forumRepository->getIndexTree();
		$topics = $this->topicRepository->getNewest();
		$users = $this->userRepository->online($settings->get('wio.minutes', 15), 'name', 'asc', 0);

		return view('forum.index', compact('forums', 'topics', 'users'));
	}

	/**
	 * Shows a specific forum.
	 *
	 * @param Request $request
	 * @param string  $slug The slug of the forum to show.
	 *
	 * @param int     $id   The ID of the forum to show.
	 *
	 * @return \Illuminate\View\View
	 */
	public function show(Request $request, $slug = '', $id = 0)
	{
		$forum = $this->forumRepository->find($id);

		if(!$forum)
		{
			throw new NotFoundHttpException(trans('errors.forum_not_found'));
		}

		Breadcrumbs::setCurrentRoute('forums.show', $forum);

		// Build the order by/dir parts
		$allowed = ['lastpost', 'replies', 'startdate', 'title'];

		$orderBy = $request->get('orderBy', 'lastpost');
		if(!in_array($orderBy, $allowed))
		{
			$orderBy = 'lastpost';
		}

		$orderDir = $request->get('orderDir', 'desc');
		if($orderDir != 'asc' && $orderDir != 'desc')
		{
			$orderDir = 'desc';
		}

		// We need to know how to build the url...
		$urlDirs = [
			'lastpost' => 'desc',
			'replies' => 'desc',
			'startdate' => 'desc',
			'title' => 'asc',
		];
		if($orderDir == 'desc' && $urlDirs[$orderBy] == 'desc')
		{
			$urlDirs[$orderBy] = 'asc';
		} elseif($orderDir == 'asc' && $urlDirs[$orderBy] == 'asc')
		{
			$urlDirs[$orderBy] = 'desc';
		}

		$topics = $this->topicRepository->allForForum($forum, $orderBy, $orderDir);

		$topics->appends(['orderBy' => $orderBy, 'orderDir' => $orderDir]);

		return view('forum.show', compact('forum', 'topics', 'orderBy', 'orderDir', 'urlDirs'));
	}
}

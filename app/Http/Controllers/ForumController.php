<?php namespace MyBB\Core\Http\Controllers;

use MyBB\Core\Database\Repositories\IForumRepository;

class ForumController extends Controller
{
	/** @var IForumRepository $forumRepository */
	private $forumRepository;

	/**
	 * Create a new controller instance.
	 *
	 * @param IForumRepository $forumRepository Forum repository instance to use in order to load forum information.
	 */
	public function __construct(IForumRepository $forumRepository)
	{
		$this->forumRepository  = $forumRepository;
	}

	/**
	 * Shows the Index Page
	 *
	 * @return Response
	 */
	public function index()
	{
		$forums = $this->forumRepository->getIndexTree();

		return view('index.index', compact('forums'));
	}

}

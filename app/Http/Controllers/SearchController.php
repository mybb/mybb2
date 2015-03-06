<?php namespace MyBB\Core\Http\Controllers;

use Illuminate\Auth\Guard;
use MyBB\Core\Database\Repositories\ITopicRepository;
use MyBB\Core\Database\Repositories\IUserRepository;
use MyBB\Core\Http\Requests\Search\SearchRequest;


class SearchController extends Controller
{
	/**
	 * @var IUserRepository $userRepository
	 * @access protected
	 */
	protected $userRepository;
	/**
	 * @var ITopicRepository $topicRepository
	 * @access protected
	 */
	protected $topicRepository;

	/**
	 * Create a new controller instance.
	 *
	 * @param Guard $guard
	 */
	public function __construct(
		Guard $guard,
		Request $request,
		ITopicRepository $topicRepository,
		IUserRepository $userRepository
	) {
		parent::__construct($guard, $request);


		$this->userRepository = $userRepository;
		$this->topicRepository = $topicRepository;
	}

	public function index()
	{
		return view('search.index');
	}

	public function makeSearch(SearchRequest $searchRequest)
	{

	}
}

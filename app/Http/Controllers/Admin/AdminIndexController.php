<?php namespace MyBB\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MyBB\Core\Database\Repositories\ForumRepositoryInterface;
use MyBB\Core\Database\Repositories\PostRepositoryInterface;
use MyBB\Core\Database\Repositories\TopicRepositoryInterface;
use MyBB\Core\Database\Repositories\UserRepositoryInterface;
use MyBB\Core\Database\Models\Forum;
use MyBB\Core\Database\Models\Post;
use MyBB\Core\Database\Models\Topic;
use MyBB\Core\Database\Models\User;
use MyBB\Auth\Contracts\Guard;
use MyBB\Settings\Store;

class AdminIndexController extends AdminController
{

	/*
	|--------------------------------------------------------------------------
	| Admin Index Controller
	|--------------------------------------------------------------------------
	|
	| Handler for the AdminCp Dashboard
	|
	|
	*/

	/**
	 * @var TopicRepositoryInterface
	 */
	private $topicRepository;

	/**
	 * @var PostRepositoryInterface
	 */
	private $postRepository;

	/**
	 * @var ForumRepositoryInterface
	 */
	private $forumRepository;

	/**
	 * @var UserRepositoryInterface
	 */
	private $userRepository;

	/**
	 * @var Guard
	 */
	private $guard;

	/**
	 * @var Store
	 */
	private $settings;

	/**
	 * Create a new controller instance.
	 *
	 * @param TopicRepositoryInterface $topicRepository Topic repository to fetch topic information
	 * @param PostRepositoryInterface  $postRepository  Post repository to fetch post information
	 * @param ForumRepositoryInterface $forumRepository Forum repository to fetch forum information
	 * @param UserRepositoryInterface  $userRepository  User repository to fetch user information
	 * @param Guard                    $guard           Guard implementation
	 * @param Store                    $settings
	 */
	public function __construct(
		TopicRepositoryInterface $topicRepository,
		PostRepositoryInterface $postRepository,
		ForumRepositoryInterface $forumRepository,
		UserRepositoryInterface $userRepository,
		Guard $guard,
		Store $settings
	) {
		parent::__construct($guard);

		$this->topicRepository = $topicRepository;
		$this->postRepository = $postRepository;
		$this->forumRepository = $forumRepository;
		$this->guard = $guard;
	}

	/**
	 * Shows the Index Page
	 *
	 * @return Response
	 */
	public function index()
	{
		return view();
	}
}

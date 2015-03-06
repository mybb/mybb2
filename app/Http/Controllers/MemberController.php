<?php namespace MyBB\Core\Http\Controllers;

use Illuminate\Auth\Guard;
use Illuminate\Http\Request;
use MyBB\Core\Database\Repositories\IUserRepository;


class MemberController extends Controller
{
	/**
	 * @var IUserRepository $userRepository
	 * @access protected
	 */
	protected $userRepository;

	/**
	 * Create a new controller instance.
	 *
	 * @param Guard $guard
	 */
	public function __construct(
		Guard $guard,
		Request $request,
		IUserRepository $userRepository
	) {
		parent::__construct($guard, $request);

		$this->userRepository = $userRepository;
	}

	public function memberlist()
	{
		$users = $this->userRepository->all();

		return view('member.list', compact('users'));
	}

	public function online()
	{
		$users = $this->userRepository->online(15, 20); // TODO both should be settings
		return view('member.online', compact('users'));
	}
}

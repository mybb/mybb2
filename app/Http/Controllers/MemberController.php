<?php namespace MyBB\Core\Http\Controllers;

use Illuminate\Auth\Guard;
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
		IUserRepository $userRepository
	) {
		parent::__construct($guard);

		$this->userRepository = $userRepository;
	}

	public function memberlist()
	{
		$users = $this->userRepository->all();

		return view('member.list', compact('users'));
	}

}

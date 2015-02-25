<?php namespace MyBB\Core\Http\Controllers;

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
		IUserRepository $userRepository
	)
	{
		$this->userRepository = $userRepository;
	}

	public function memberlist()
	{
		$users = $this->userRepository->all();
		return view('member.list', compact('users'));
	}

}

<?php namespace MyBB\Core\Http\Controllers;

use Illuminate\Auth\Guard;
use Illuminate\Http\Request;
use MyBB\Core\Database\Repositories\UserRepositoryInterface;
use MyBB\Settings\Store;


class MemberController extends Controller
{
	/**
	 * @var UserRepositoryInterface $userRepository
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
		UserRepositoryInterface $userRepository
	) {
		parent::__construct($guard, $request);

		$this->userRepository = $userRepository;
	}

	public function memberlist()
	{
		$users = $this->userRepository->all();

		return view('member.list', compact('users'));
	}

	public function online(Store $settings)
	{
		$users = $this->userRepository->online($settings->get('wio.minutes', 15));
		return view('member.online', compact('users'));
	}
}

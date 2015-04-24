<?php namespace MyBB\Core\Http\Controllers;

use MyBB\Core\Database\Repositories\UserRepositoryInterface;
use MyBB\Settings\Store;

class MemberController extends AbstractController
{
	/**
	 * @var UserRepositoryInterface $userRepository
	 */
	protected $userRepository;

	/**
	 * Create a new controller instance.
	 *
	 * @param UserRepositoryInterface $userRepository
	 */
	public function __construct(UserRepositoryInterface $userRepository)
	{
		$this->userRepository = $userRepository;
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function memberlist()
	{
		$users = $this->userRepository->all();

		return view('member.list', compact('users'));
	}

	/**
	 * @param Store $settings
	 *
	 * @return \Illuminate\View\View
	 */
	public function online(Store $settings)
	{
		$users = $this->userRepository->online($settings->get('wio.minutes', 15));

		return view('member.online', compact('users'));
	}
}

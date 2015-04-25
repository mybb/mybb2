<?php namespace MyBB\Core\Http\Controllers;

use Illuminate\Http\Request;
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
	 * @param Store   $settings
	 * @param Request $request
	 *
	 * @return \Illuminate\View\View
	 */
	public function memberlist(Store $settings, Request $request)
	{
		$sortBy = $settings->get('memberlist.sort_by', 'created_at', false);
		$sortDir = $settings->get('memberlist.sort_dir', 'asc', false);
		$perPage = $settings->get('memberlist.per_page', 10, false);

		if ($request->has('sortBy')) {
			$sortBy = $request->get('sortBy');
		}

		if ($request->has('sortDir')) {
			$sortDir = $request->get('sortDir');
		}

		if ($request->has('perPage')) {
			$perPage = $request->get('perPage');
		}

		$users = $this->userRepository->all($sortBy, $sortDir, $perPage);

		return view('member.list', compact('users', 'sortBy', 'sortDir'));
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

<?php
/**
 * User repository implementation, using Eloquent ORM.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Repositories\Eloquent;

use MyBB\Core\Database\Models\User;
use MyBB\Core\Database\Repositories\IUserRepository;

class UserRepository implements IUserRepository
{
	/**
	 * @var User $userModel
	 * @access protected
	 */
	protected $userModel;

	/**
	 * @param User $userModel The model to use for users.
	 */
	public function __construct(
		User $userModel
	) {
		$this->userModel = $userModel;
	}

	/**
	 * Get all users.
	 *
	 * @return mixed
	 */
	public function all()
	{
		return $this->userModel->paginate(10);
	}

	/**
	 * Get all users active in the last x minutes
	 *
	 * @param int $minutes
	 * @param int $num
	 *
	 * @return mixed
	 */
	public function online($minutes = 15, $num = 20)
	{
		// If the user visited the logout page as last he's not online anymore
		return $this->userModel->where('last_visit', '>=', new \DateTime("{$minutes} minutes ago"))
			->where('last_page', '!=', 'auth/logout')
			->orderBy('last_visit', 'desc')
			->paginate($num);
	}

	/**
	 * Find a single user by ID.
	 *
	 * @param int $id The ID of the user to find.
	 *
	 * @return mixed
	 */
	public function find($id = 0)
	{
		return $this->userModel->find($id);
	}

	/**
	 * Find a single user by its username.
	 *
	 * @param string $username The username of the user. Eg: 'admin'.
	 *
	 * @return mixed
	 */
	public function findByUsername($username = '')
	{
		return $this->userModel->whereNname($username)->first();
	}

}

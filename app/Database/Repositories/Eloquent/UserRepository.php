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

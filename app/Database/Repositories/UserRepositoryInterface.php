<?php
/**
 * User repository contract.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Repositories;

interface UserRepositoryInterface
{
	/**
	 * Get all users.
	 *
	 * @return mixed
	 */
	public function all();

	/**
	 * Get all users active in the last x minutes
	 *
	 * @param int    $minutes The number of minutes which are considered as "online time"
	 * @param string $orderBy
	 * @param string $orderDir
	 * @param int    $num     The number of users to return. Set to 0 to get all users
	 *
	 * @return mixed
	 */
	public function online($minutes = 15, $orderBy = 'last_visit', $orderDir = 'desc', $num = 20);

	/**
	 * Find a single user by ID.
	 *
	 * @param int $id The ID of the user to find.
	 *
	 * @return mixed
	 */
	public function find($id = 0);

	/**
	 * Find a single user by its username.
	 *
	 * @param string $username The username of the user. Eg: 'admin'.
	 *
	 * @return mixed
	 */
	public function findByUsername($username = '');
}

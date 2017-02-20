<?php
/**
 * User repository contract.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Repositories;

use MyBB\Core\Database\Models\User;

interface UserRepositoryInterface
{
    /**
     * Get all users.
     *
     * @param string $sortBy
     * @param string $sortDir
     * @param int $perPage
     *
     * @return mixed
     */
    public function all(string $sortBy = 'created_at', string $sortDir = 'desc', int $perPage = 10);
    
    /**
     * Get all users who match a certain set of criteria.
     *
     * @param string username
     * @param string email
     * @param int role_id
     * @param string $sortBy
     * @param string $sortDir
     * @param int $perPage
     *
     * @return mixed
     */
    public function search(string $username = "", string $email = "", int $role_id = 0, string $sortBy = 'created_at', string $sortDir = 'asc', int $perPage = 10);
    
    /**
     * Get all users active in the last x minutes
     *
     * @param int $minutes The number of minutes which are considered as "online time"
     * @param string $orderBy
     * @param string $orderDir
     * @param int $num The number of users to return. Set to 0 to get all users
     *
     * @return mixed
     */
    public function online(int $minutes = 15, string $orderBy = 'last_visit', string $orderDir = 'desc', int $num = 20);

    /**
     * Find a single user by ID.
     *
     * @param int $id The ID of the user to find.
     *
     * @return mixed
     */
    public function find(int $id = 0);

    /**
     * Find a single user by its username.
     *
     * @param string $username The username of the user. Eg: 'admin'.
     *
     * @return mixed
     */
    public function findByUsername(string $username = '');

    /**
     * Create a new user
     *
     * @param array $details Details about the user.
     *
     * @return User
     */
    public function create(array $details = []) : User;

    /**
     * Update user
     *
     * @param User $user The user to edit
     * @param array $userDetails The details of the user.
     *
     * @return User
     */
    public function update(User $user, array $userDetails = []) : User;
    
    /**
     * Delete a user
     *
     * @param int $id The ID of the user you want to delete.
     *
     * @return mixed
     */
    public function delete(int $id = 0);
}

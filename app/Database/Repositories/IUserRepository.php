<?php
/**
 * User repository contract.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Repositories;

interface IUserRepository
{
    /**
     * Get all users.
     *
     * @return mixed
     */
    public function all();

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

<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Repositories;

interface WarningsRepositoryInterface
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
    //public function allForUser($user_id);

    /**
     * Create new warning
     *
     * @param array $details
     * @return mixed
     */
    public function create(array $details = []);

    public function findForUser($userId);

    public function find($warnId);

}

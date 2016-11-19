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
     * Create new warning
     *
     * @param array $details
     * @return mixed
     */
    public function create(array $details = []);

    /**
     * Get all warnings for user.
     *
     * @param int $userId
     * @return mixed
     */
    public function findForUser($userId);

    /**
     * Find warning by id
     *
     * @param int $warnId
     * @return mixed
     */
    public function find($warnId);
}

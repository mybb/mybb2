<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Repositories;

use MyBB\Core\Database\Models\Warning;

interface WarningsRepositoryInterface
{

    /**
     * Create new warning
     *
     * @param array $details
     * @return Warning
     */
    public function create(array $details = []);

    /**
     * Get all warnings for user.
     *
     * @param int $userId
     * @return Warning
     */
    public function findForUser($userId);

    /**
     * Find warning by id
     *
     * @param int $warnId
     * @return Warning
     */
    public function find($warnId);

    /**
     * Revoke warning
     *
     * @param Warning $warning
     * @param string $reason
     * @return Warning
     */
    public function revoke(Warning $warning, $reason);
}

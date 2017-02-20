<?php
/**
 * Role repository contract.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Repositories;

interface RoleRepositoryInterface
{
    /**
     * Get all roles.
     *
     * @return mixed
     */
    public function all();

    /**
     * Find a single role by its slug.
     *
     * @param string|null $slug The slug of the role. Eg: 'user'.
     *
     * @return mixed
     */
    public function findIdBySlug($slug = '');
}

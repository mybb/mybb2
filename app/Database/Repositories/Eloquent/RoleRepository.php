<?php
/**
 * Role repository implementation, using Eloquent ORM.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Repositories\Eloquent;

use MyBB\Core\Database\Models\Role;
use MyBB\Core\Database\Repositories\RoleRepositoryInterface;


class RoleRepository implements RoleRepositoryInterface
{
    /**
     * @var Role $roleModel
     */
    protected $roleModel;


    /**
     * RoleRepository constructor.
     * @param Role $roleModel The model to use for roles.
     */
    public function __construct(
        Role $roleModel
    ) {
        $this->roleModel = $roleModel;
    }

    /**
     * Get all roles.
     *
     * @return mixed
     */
    public function all()
    {
        return $this->roleModel->all();
    }

    /**
     * Find a single role id by its slug.
     *
     * @param string $slug The slug of the role. Eg: 'user'.
     *
     * @return mixed
     */
    public function findIdBySlug($slug = '')
    {
        return $this->roleModel->where('role_slug', '=', $slug)->value('id');
    }

}

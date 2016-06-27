<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Repositories;

use Illuminate\Support\Collection;
use MyBB\Core\Database\Models\ProfileField;
use MyBB\Core\Database\Models\ProfileFieldGroup;

interface ProfileFieldRepositoryInterface
{
    /**
     * @param array $data
     *
     * @return ProfileField
     */
    public function create(array $data);

    /**
     * @param int $id
     *
     * @return ProfileField
     */
    public function find($id);

    /**
     * @param int $id
     */
    public function delete($id);

    /**
     * @return Collection
     */
    public function getAll();

    /**
     * @param ProfileFieldGroup $group
     *
     * @return Collection
     */
    public function getForGroup(ProfileFieldGroup $group);
}

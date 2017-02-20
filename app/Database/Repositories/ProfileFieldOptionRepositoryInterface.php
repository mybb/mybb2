<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Repositories;

use MyBB\Core\Database\Models\ProfileField;
use MyBB\Core\Database\Models\ProfileFieldOption;

interface ProfileFieldOptionRepositoryInterface
{
    /**
     * @param int $id
     *
     * @return ProfileFieldOption
     */
    public function find(int $id) : ProfileFieldOption;

    /**
     * @param array $data
     *
     * @return ProfileFieldOption
     */
    public function create(array $data) : ProfileFieldOption;

    /**
     * @param ProfileField $profileField
     *
     * @return ProfileFieldOption[]
     */
    public function getForProfileField(ProfileField $profileField);
}

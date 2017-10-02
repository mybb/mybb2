<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Repositories;

use Illuminate\Support\Collection;
use MyBB\Core\Database\Models\{
    ProfileField, ProfileFieldGroup, User, UserProfileField
};

interface UserProfileFieldRepositoryInterface
{
    /**
     * @param User $user
     * @param ProfileField $profileField
     * @param string $value
     *
     * @return UserProfileField
     */
    public function create(User $user, ProfileField $profileField, string $value) : UserProfileField;

    /**
     * @param User $user
     * @param ProfileField $profileField
     * @param string $value
     *
     * @return UserProfileField
     */
    public function updateOrCreate(User $user, ProfileField $profileField, string $value) : UserProfileField;

    /**
     * @param int $id
     *
     * @return UserProfileField
     */
    public function find(int $id) : UserProfileField;

    /**
     * @param User $user
     *
     * @return Collection
     */
    public function findForUser(User $user) : Collection;

    /**
     * @param User $user
     * @param ProfileField $profileField
     *
     * @return UserProfileField
     */
    public function findForProfileField(User $user, ProfileField $profileField) : UserProfileField;

    /**
     * @param User $user
     * @param ProfileFieldGroup $group
     *
     * @return Collection
     */
    public function findForProfileFieldGroup(User $user, ProfileFieldGroup $group) : Collection;

    /**
     * @param User $user
     * @param ProfileField $profileField
     *
     * @return bool
     */
    public function hasForProfileField(User $user, ProfileField $profileField) : bool;
}

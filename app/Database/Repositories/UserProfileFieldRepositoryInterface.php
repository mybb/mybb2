<?php

namespace MyBB\Core\Database\Repositories;

use Illuminate\Support\Collection;
use MyBB\Core\Database\Models\ProfileField;
use MyBB\Core\Database\Models\User;
use MyBB\Core\Database\Models\UserProfileField;

interface UserProfileFieldRepositoryInterface
{
    /**
     * @param User $user
     * @param ProfileField $profileField
     * @param string $value
     * @return UserProfileField
     */
    public function create(User $user, ProfileField $profileField, $value);

    /**
     * @param User $user
     * @param ProfileField $profileField
     * @param string $value
     * @return UserProfileField
     */
    public function updateOrCreate(User $user, ProfileField $profileField, $value);

    /**
     * @param int $id
     * @return UserProfileField
     */
    public function find($id);

    /**
     * @param User $user
     * @return Collection
     */
    public function findForUser(User $user);

    /**
     * @param User $user
     * @param ProfileField $profileField
     * @return UserProfileField
     */
    public function findForProfileField(User $user, ProfileField $profileField);
}

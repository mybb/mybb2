<?php

namespace MyBB\Core\Database\Repositories\Eloquent;

use Illuminate\Support\Collection;
use MyBB\Core\Database\Models\ProfileField;
use MyBB\Core\Database\Models\User;
use MyBB\Core\Database\Models\UserProfileField;
use MyBB\Core\Database\Repositories\UserProfileFieldRepositoryInterface;

class UserProfileFieldRepository implements UserProfileFieldRepositoryInterface
{
    /**
     * @param User $user
     * @param ProfileField $profileField
     * @param string $value
     * @return UserProfileField
     */
    public function create(User $user, ProfileField $profileField, $value)
    {
        return UserProfileField::create([
            'user_id' => $user->getId(),
            'profile_field_id' => $profileField->getId(),
            'value' => $value
        ]);
    }

    /**
     * @param int $id
     * @return UserProfileField
     */
    public function find($id)
    {
        return UserProfileField::find($id);
    }

    /**
     * @param User $user
     * @param ProfileField $profileField
     * @return UserProfileField
     */
    public function findForProfileField(User $user, ProfileField $profileField)
    {
        return UserProfileField::where('user_id', $user->getId())
            ->where('profile_field_id', $profileField->getId())
            ->get()
            ->first();
    }

    /**
     * @param User $user
     * @param ProfileField $profileField
     * @param string $value
     * @return UserProfileField
     */
    public function updateOrCreate(User $user, ProfileField $profileField, $value)
    {
        $userProfileField = $this->findForProfileField($user, $profileField);

        if ($userProfileField) {
            $userProfileField->setValue($value);
            $userProfileField->save();
            return $userProfileField;
        }

        return $this->create($user, $profileField, $value);
    }

    /**
     * @param User $user
     * @return Collection
     */
    public function findForUser(User $user)
    {
        return UserProfileField::where('user_id', $user->getId())->get();
    }
}

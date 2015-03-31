<?php

namespace MyBB\Core\Database\Repositories\Eloquent;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use MyBB\Core\Database\Models\ProfileField;
use MyBB\Core\Database\Models\ProfileFieldGroup;
use MyBB\Core\Database\Models\User;
use MyBB\Core\Database\Models\UserProfileField;
use MyBB\Core\Database\Repositories\UserProfileFieldRepositoryInterface;

class UserProfileFieldRepository implements UserProfileFieldRepositoryInterface
{
    /**
     * @var UserProfileField
     */
    protected $userProfileField;

    /**
     * @param UserProfileField $userProfileField
     */
    public function __construct(UserProfileField $userProfileField)
    {
        $this->userProfileField = $userProfileField;
    }

    /**
     * @param User $user
     * @param ProfileField $profileField
     * @param string $value
     *
     * @return UserProfileField
     */
    public function create(User $user, ProfileField $profileField, $value)
    {
        return $this->userProfileField->create([
            'user_id' => $user->getId(),
            'profile_field_id' => $profileField->id,
            'value' => $value
        ]);
    }

    /**
     * @param int $id
     *
     * @return UserProfileField
     */
    public function find($id)
    {
        return $this->userProfileField->find($id);
    }

    /**
     * @param User $user
     * @param ProfileField $profileField
     *
     * @return UserProfileField
     */
    public function findForProfileField(User $user, ProfileField $profileField)
    {
        return $this->userProfileField->where('user_id', $user->getId())
            ->where('profile_field_id', $profileField->id)
            ->get()
            ->first();
    }

    /**
     * @param User $user
     * @param ProfileField $profileField
     * @param string $value
     *
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
     *
     * @return Collection
     */
    public function findForUser(User $user)
    {
        return $this->userProfileField->where('user_id', $user->getId())->get();
    }

    /**
     * @param User $user
     * @param ProfileFieldGroup $group
     *
     * @return Collection
     */
    public function findForProfileFieldGroup(User $user, ProfileFieldGroup $group)
    {
        $userFields = $this->userProfileField->where('user_id', $user->getId())
            ->whereHas('getProfileField', function (Builder $q) use ($group) {
                $q->where('profile_field_group_id', $group->id);
            })
            ->get();

        return $userFields;
    }

    /**
     * @param User $user
     * @param ProfileField $profileField
     *
     * @return bool
     */
    public function hasForProfileField(User $user, ProfileField $profileField)
    {
        return $this->userProfileField->where('user_id', $user->getId())
            ->where('profile_field_id', $profileField->id)
            ->count() > 0;
    }
}

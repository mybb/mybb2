<?php

namespace MyBB\Core\Database\Repositories\Eloquent;

use Illuminate\Support\Collection;
use MyBB\Core\Database\Models\ProfileFieldGroup;
use MyBB\Core\Database\Repositories\ProfileFieldGroupRepositoryInterface;

class ProfileFieldGroupRepository implements ProfileFieldGroupRepositoryInterface
{
    /**
     * @var ProfileFieldGroup
     */
    protected $profileFieldGroup;

    /**
     * @param ProfileFieldGroup $profileFieldGroup
     */
    public function __construct(ProfileFieldGroup $profileFieldGroup)
    {
        $this->profileFieldGroup = $profileFieldGroup;
    }

    /**
     * @param string $slug
     * @return ProfileFieldGroup
     */
    public function getBySlug($slug)
    {
        return $this->profileFieldGroup->where('slug', $slug)->first();
    }

    /**
     * @return Collection
     */
    public function getAll()
    {
        return $this->profileFieldGroup->all();
    }
}

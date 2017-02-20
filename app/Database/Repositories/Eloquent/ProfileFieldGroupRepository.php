<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

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
     *
     * @return ProfileFieldGroup
     */
    public function getBySlug(string $slug) : ProfileFieldGroup
    {
        return $this->profileFieldGroup->where('slug', $slug)->first();
    }

    /**
     * @return Collection
     */
    public function getAll() : Collection
    {
        return $this->profileFieldGroup->all();
    }

    /**
     * @return array
     */
    public function getAllForSelectElement() : array
    {
        return $this->profileFieldGroup->lists('name', 'id');
    }

    /**
     * @param array $data
     *
     * @return ProfileFieldGroup
     */
    public function create(array $data) : ProfileFieldGroup
    {
        return $this->profileFieldGroup->create($data);
    }
}

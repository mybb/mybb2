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

    /**
     * @return array
     */
    public function getAllForSelectElement()
    {
        return $this->profileFieldGroup->lists('name', 'id');
    }

    /**
     * @param array $data
     *
     * @return ProfileFieldGroup
     */
    public function create(array $data)
    {
        return $this->profileFieldGroup->create($data);
    }
}

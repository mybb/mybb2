<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Repositories\Eloquent;

use Illuminate\Support\Collection;
use MyBB\Core\Database\Models\ProfileField;
use MyBB\Core\Database\Models\ProfileFieldOption;
use MyBB\Core\Database\Repositories\ProfileFieldOptionRepositoryInterface;

class ProfileFieldOptionRepository implements ProfileFieldOptionRepositoryInterface
{
    /**
     * @var ProfileFieldOption
     */
    protected $profileFieldOption;

    /**
     * @param ProfileFieldOption $profileFieldOption
     */
    public function __construct(ProfileFieldOption $profileFieldOption)
    {
        $this->profileFieldOption = $profileFieldOption;
    }

    /**
     * @param int $id
     *
     * @return ProfileFieldOption
     */
    public function find(int $id) : ProfileFieldOption
    {
        return $this->profileFieldOption->newQuery()->find($id);
    }

    /**
     * @param array $data
     *
     * @return ProfileFieldOption
     */
    public function create(array $data) : ProfileFieldOption
    {
        return $this->profileFieldOption->create($data);
    }

    /**
     * @param ProfileField $profileField
     *
     * @return Collection
     */
    public function getForProfileField(ProfileField $profileField) : Collection
    {
        return $this->profileFieldOption->newQuery()->where('profile_field_id', $profileField->id)->get();
    }
}

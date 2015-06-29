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
use MyBB\Core\Database\Models\ProfileFieldGroup;
use Mybb\Core\Database\Repositories\ProfileFieldRepositoryInterface;

class ProfileFieldRepository implements ProfileFieldRepositoryInterface
{
	/**
	 * @var ProfileField
	 */
	protected $profileField;

	/**
	 * @param ProfileField $profileField
	 */
	public function __construct(ProfileField $profileField)
	{
		$this->profileField = $profileField;
	}

	/**
	 * @param array $data
	 *
	 * @return ProfileField
	 */
	public function create(array $data)
	{
		return $this->profileField->create($data);
	}

	/**
	 * @return Collection
	 */
	public function getAll()
	{
		return $this->profileField->all();
	}

	/**
	 * @param int $id
	 *
	 * @return ProfileField
	 */
	public function find($id)
	{
		return $this->profileField->find($id);
	}

	/**
	 * @param ProfileFieldGroup $group
	 *
	 * @return Collection
	 */
	public function getForGroup(ProfileFieldGroup $group)
	{
		return $this->profileField->where('profile_field_group_id', $group->getId())->get();
	}

	/**
	 * @param int $id
	 */
	public function delete($id)
	{
		$this->profileField->newQuery()->getQuery()->delete($id);
	}
}

<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Requests\Account;

use MyBB\Core\Database\Models\ProfileField;
use MyBB\Core\Database\Repositories\ProfileFieldGroupRepositoryInterface;
use MyBB\Core\Database\Repositories\ProfileFieldRepositoryInterface;
use MyBB\Core\Http\Requests\AbstractRequest;

class UpdateProfileRequest extends AbstractRequest
{
	/**
	 * @var ProfileFieldRepositoryInterface
	 */
	protected $profileFieldsRepository;

	/**
	 * @var array
	 */
	protected $profileFields;

	/**
	 * @var array
	 */
	protected $allProfileFields;

	/**
	 * @var ProfileFieldGroupRepositoryInterface
	 */
	protected $profileFieldGroupRepository;

	/**
	 * @param ProfileFieldRepositoryInterface      $profileFieldsRepository
	 * @param ProfileFieldGroupRepositoryInterface $profileFieldGroupRepository
	 */
	public function __construct(
		ProfileFieldRepositoryInterface $profileFieldsRepository,
		ProfileFieldGroupRepositoryInterface $profileFieldGroupRepository
	) {
		$this->profileFieldsRepository = $profileFieldsRepository;
		$this->profileFieldGroupRepository = $profileFieldGroupRepository;
	}

	/**
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * @return array
	 */
	public function rules()
	{
		$rules = [
			'date_of_birth_day' => 'integer|min:1|max:31',
			'date_of_birth_month' => 'integer|min:1|max:12',
			'date_of_birth_year' => 'integer',
			'usertitle' => 'string',
		];

		foreach ($this->getAllProfileFields() as $profileField) {
			if ($profileField->validation_rules) {
				$rules['profile_fields.' . $profileField->id] = $profileField->validation_rules;
			}
		}

		return $rules;
	}

	/**
	 * @return array
	 */
	public function attributes()
	{
		$attributes = [];

		foreach ($this->getAllProfileFields() as $profileField) {
			$attributes['profile_fields.' . $profileField->id] = $profileField->name;
		}

		return $attributes;
	}

	/**
	 * @return ProfileField[]
	 */
	public function getProfileFields()
	{
		if (!$this->profileFields) {
			$profileFieldData = $this->get('profile_fields');
			$this->profileFields = [];

			foreach ($profileFieldData as $profileFieldId => $value) {
				if ($value !== '') {
					$this->profileFields[] = $this->profileFieldsRepository->find($profileFieldId);
				}
			}
		}

		return $this->profileFields;
	}

	/**
	 * @return array
	 */
	protected function getAllProfileFields()
	{
		if (!$this->allProfileFields) {
			foreach ($this->profileFieldGroupRepository->getAll() as $profileFieldGroup) {
				foreach ($profileFieldGroup->getProfileFields()->get() as $profileField) {
					$this->allProfileFields[] = $profileField;
				}
			}
		}

		return $this->allProfileFields;
	}
}

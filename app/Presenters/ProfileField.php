<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Presenters;

use McCool\LaravelAutoPresenter\BasePresenter;
use MyBB\Auth\Contracts\Guard;
use MyBB\Core\Database\Models\ProfileField as ProfileFieldModel;
use MyBB\Core\Database\Models\ProfileFieldOption;
use MyBB\Core\Database\Repositories\UserProfileFieldRepositoryInterface;
use MyBB\Core\Form\RenderableInterface;

class ProfileField extends BasePresenter implements RenderableInterface
{
	/**
	 * @var Guard
	 */
	protected $guard;

	/**
	 * @var UserProfileFieldRepositoryInterface
	 */
	protected $userProfileFields;

	/**
	 * @param ProfileFieldModel                   $resource
	 * @param Guard                               $guard
	 * @param UserProfileFieldRepositoryInterface $userProfileFields
	 */
	public function __construct(
		ProfileFieldModel $resource,
		Guard $guard,
		UserProfileFieldRepositoryInterface $userProfileFields
	) {
		parent::__construct($resource);

		$this->guard = $guard;
		$this->userProfileFields = $userProfileFields;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @return array
	 */
	public function getOptions()
	{
		$options = ProfileFieldOption::getForProfileField($this->getWrappedObject());

		$formattedOptions = [];

		foreach ($options as $option) {
			$formattedOptions[$option->getValue()] = $option->getName();
		}

		return $formattedOptions;
	}

	/**
	 * @return string
	 */
	public function getElementName()
	{
		return 'profile_fields[' . $this->id . ']';
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @return string
	 */
	public function getLabel()
	{
		return $this->name;
	}

	/**
	 * @param User $user
	 *
	 * @return mixed
	 */
	public function getValue(User $user = null)
	{
		if ($user == null) {
			$user = $this->guard->user();
		} else {
			$user = $user->getWrappedObject();
		}

		$userProfileField = $this->userProfileFields->findForProfileField($user, $this->getWrappedObject());

		if ($userProfileField) {
			return $userProfileField->getValue();
		}
	}

	/**
	 * @return bool
	 */
	public function has_value()
	{
		return $this->userProfileFields->hasForProfileField($this->guard->user(), $this->getWrappedObject());
	}

	/**
	 * @return array
	 */
	public function getValidationRules()
	{
		return $this->validation_rules;
	}
}

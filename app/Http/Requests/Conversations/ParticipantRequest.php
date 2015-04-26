<?php
/**
 * Topic create request.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Http\Requests\Conversations;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use MyBB\Auth\Contracts\Guard;
use MyBB\Core\Database\Models\User;
use MyBB\Core\Http\Requests\AbstractRequest;

class ParticipantRequest extends AbstractRequest
{
	/**
	 * @var Guard
	 */
	private $guard;

	/**
	 * @param Guard $guard
	 */
	public function __construct(Guard $guard)
	{
		$this->guard = $guard;
	}

	/**
	 * Get the validator instance for the request.
	 *
	 * @return \Illuminate\Validation\Validator
	 */
	protected function getValidatorInstance()
	{
		$validator = parent::getValidatorInstance();

		$validator->addImplicitExtension('usernameArray', function ($attribute, $value, $parameters) {
			try {
				$this->getUseridArray($attribute);
			} catch (\Exception $e) {
				return false;
			}

			return true;
		});


		return $validator;
	}

	/**
	 * @param string $attribute
	 *
	 * @return array
	 */
	public function getUsernameArray($attribute)
	{
		$value = $this->input($attribute);

		if (is_array($value)) {
			return $value;
		}

		return array_map('trim', explode(',', $value));
	}

	/**
	 * @param string $attribute
	 *
	 * @return array
	 *
	 * @throws ModelNotFoundException
	 */
	public function getUseridArray($attribute)
	{
		$usernames = $this->getUsernameArray($attribute);

		$userids = array();
		foreach ($usernames as $username) {
			$user = User::where('name', $username)->first();

			if (!$user) {
				throw new ModelNotFoundException;
			}

			$userids[] = $user->id;
		}

		return $userids;
	}

	/**
	 * @return array
	 */
	public function rules()
	{
		return [
			'participants' => 'required|usernameArray',
		];
	}

	/**
	 * @return bool
	 */
	public function authorize()
	{
		//return $this->guard->check();
		return true; // TODO: In dev return, needs replacing for later...
	}
}

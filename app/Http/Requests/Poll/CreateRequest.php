<?php
/**
 * Create request.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Http\Requests\Poll;

use Illuminate\Contracts\Auth\Guard;
use MyBB\Core\Http\Requests\Request;

class CreateRequest extends AbstractRequest
{
	/**
	 * @var Guard
	 */
	private $guard;

	/**
	 * Get the validator instance for the request.
	 *
	 * @return \Illuminate\Validation\Validator
	 */
	protected function getValidatorInstance()
	{
		$validator = parent::getValidatorInstance();

		$validator->addImplicitExtension('option', function ($attribute, $value, $parameters) {
			foreach ($value as $option) {
				if (!is_scalar($option)) {
					return false;
				}
			}

			return true;
		});


		return $validator;
	}

	/**
	 * @param Guard $guard
	 */
	public function __construct(Guard $guard)
	{
		$this->guard = $guard;
	}

	/**
	 * @return array
	 */
	public function rules()
	{
		$rules = [
			'question' => 'required',
			'option' => ['required', 'array', 'option'],
			'is_multiple' => 'boolean',
			'is_public' => 'boolean',
			'is_closed' => 'boolean',
			'maxoptions' => 'required_with:is_multiple|integer|min:0',
			'endAt' => 'date'
		];

		return $rules;
	}

	/**
	 * get the options of the poll
	 *
	 * @return array
	 */
	public function options()
	{
		$input = $this->input('option');
		$options = [];
		foreach ($input as $option) {
			if ($option) {
				$options[] = [
					'option' => $option,
					'votes' => 0
				];
			}
		}

		return $options;
	}

	/**
	 * @return array
	 */
	public function messages()
	{
		return [
			'option.option' => trans('errors.poll_invalid_options'),
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

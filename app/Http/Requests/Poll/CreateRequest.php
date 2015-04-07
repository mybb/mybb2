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

class CreateRequest extends Request
{
	/** @var Guard $guard */
	private $guard;

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
		return [
			'question' => 'required',
			'option' => 'required|array',
			'is_multiple' => 'boolean',
			'is_public' => 'boolean',
			'maxoptions' => 'integer|min:0',
			'endAt' => 'date'
		];
	}

	/**
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}
}

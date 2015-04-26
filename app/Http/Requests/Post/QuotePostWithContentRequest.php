<?php

namespace MyBB\Core\Http\Requests\Post;

use MyBB\Auth\Contracts\Guard;
use MyBB\Core\Http\Requests\AbstractRequest;

class QuotePostWithContentRequest extends AbstractRequest
{
	/**
	 * Validation rules for the request.
	 *
	 * @var array
	 */
	protected $rules = [
		'postid' => 'required|integer',
		'content' => 'required'
	];

	/**
	 * Get the validation rules.
	 *
	 * @return array
	 */
	public function rules()
	{
		return $this->rules;
	}

	/**
	 * Check whether the current user has permission to perform this request.
	 *
	 * @param Guard $guard
	 *
	 * @return bool
	 */
	public function authorize(Guard $guard)
	{
		// TODO: Check user permissions here...

		return true;
	}
}

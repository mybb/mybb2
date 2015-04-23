<?php
/**
 * Request to toggle a like for a post.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2014, MyBB Group
 * @license   http://www.mybb.com/about/license GNU LESSER GENERAL PUBLIC LICENSE
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\Http\Requests\Post;

use MyBB\Auth\Contracts\Guard;
use MyBB\Core\Http\Requests\Request;

class QuotePostRequest extends Request
{
	/**
	 * Validation rules for the request.
	 *
	 * @var array
	 */
	protected $rules = [
		'posts' => 'required|array',
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

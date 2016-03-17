<?php
/**
 * Request to toggle a like for a post.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2015, MyBB Group
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\Http\Requests\Post;

use Illuminate\Contracts\Auth\Guard;
use MyBB\Core\Http\Requests\AbstractRequest;

class QuotePostRequest extends AbstractRequest
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

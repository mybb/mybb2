<?php
/**
 * Topic reply request.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Http\Requests\Topic;

use MyBB\Auth\Contracts\Guard;
use MyBB\Core\Http\Requests\AbstractRequest;

class ReplyRequest extends AbstractRequest
{
	/**
	 * The route to redirect to if validation fails.
	 *
	 * @var string
	 */
	protected $redirectRoute = 'topics.reply';
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
	 * @return array
	 */
	public function rules()
	{
		return [
			'content' => 'required',
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

	/**
	 * @return string
	 */
	protected function getRedirectUrl()
	{
		return $this->redirector->getUrlGenerator()->route($this->redirectRoute, $this->route()->parameters());
	}
}

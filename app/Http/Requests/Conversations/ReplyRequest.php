<?php
/**
 * Topic reply request.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Http\Requests\Conversations;

use MyBB\Auth\Contracts\Guard;
use MyBB\Core\Http\Requests\Request;

class ReplyRequest extends Request
{
	/**
	 * The route to redirect to if validation fails.
	 *
	 * @var string
	 */
	protected $redirectRoute = 'conversations.read';
	/** @var Guard $guard */
	private $guard;

	public function __construct(Guard $guard)
	{
		$this->guard = $guard;
	}

	public function rules()
	{
		return [
			'message' => 'required',
		];
	}

	public function authorize()
	{
		//return $this->guard->check();
		return true; // TODO: In dev return, needs replacing for later...
	}

	protected function getRedirectUrl()
	{
		return $this->redirector->getUrlGenerator()->route($this->redirectRoute, $this->route()->parameters());
	}
}

<?php
/**
 * Topic create request.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */


namespace MyBB\Core\Http\Requests\Topic;


use Illuminate\Contracts\Auth\Guard;
use MyBB\Core\Http\Requests\Request;

class CreateRequest extends Request
{
	/**
	 * The route to redirect to if validation fails.
	 *
	 * @var string
	 */
	protected $redirectRoute = 'topics.show';
	/** @var Guard $guard */
	private $guard;

	public function __construct(Guard $guard)
	{
		$this->guard = $guard;
	}

	public function rules()
	{
		return [
			'content' => 'required',
			'title' => 'required',
			'forum_id' => 'required|exists:forums,id',
		];
	}

	public function authorize()
	{
		//return $this->guard->check();
		return true; // TODO: In dev return, needs replacing for later...
	}
}

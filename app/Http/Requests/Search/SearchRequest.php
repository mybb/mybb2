<?php
/**
 * Search request.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Http\Requests\Search;


use Illuminate\Contracts\Auth\Guard;
use MyBB\Core\Http\Requests\Request;

class SearchRequest extends Request
{
	/**
	 * The route to redirect to if validation fails.
	 *
	 * @var string
	 */
	protected $redirectRoute = 'search';
	/** @var Guard $guard */
	private $guard;

	public function __construct(Guard $guard)
	{
		$this->guard = $guard;
	}

	public function rules()
	{
		return [
			'keyword' => 'required|min:3',
			'author' => '',
			'matchusername' => 'boolean',
			'topic_replies_type' => 'in:atmost,atleast,exactly',
			'topic_replies' => 'integer',
			'post_date' => 'in:anydate,yesterday,oneweek,twoweek,onemonth,threemonth,sixmonth,oneyear',
			'post_date_type' => 'in:newer,older',
			'sortby' => 'in:postdate,author,subject,forum',
			'sorttype' => 'in:asc,desc',
			'result' => 'in:topics,posts',
			'forums' => 'array'
		];
	}

	public function authorize()
	{
		//return $this->guard->check();
		return true; // TODO: In dev return, needs replacing for later...
	}
}

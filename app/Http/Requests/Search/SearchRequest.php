<?php
/**
 * Search request.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Requests\Search;

use Illuminate\Contracts\Auth\Guard;
use MyBB\Core\Http\Requests\AbstractRequest;

class SearchRequest extends AbstractRequest
{
	/**
	 * The route to redirect to if validation fails.
	 *
	 * @var string
	 */
	protected $redirectRoute = 'search';
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

	/**
	 * @return bool
	 */
	public function authorize()
	{
		//return $this->guard->check();
		return true; // TODO: In dev return, needs replacing for later...
	}
}

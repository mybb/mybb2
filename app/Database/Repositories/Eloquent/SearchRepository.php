<?php
/**
 * Search repository implementation, using Eloquent ORM.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Repositories\Eloquent;

use MyBB\Auth\Contracts\Guard;
use MyBB\Core\Database\Models\Search;
use MyBB\Core\Database\Repositories\ISearchRepository;

class SearchRepository implements ISearchRepository
{
	/**
	 * @var Search $searchModel
	 * @access protected
	 */
	protected $searchModel;
	/**
	 * @var Guard $guard ;
	 * @access protected
	 */
	protected $guard;

	/**
	 * @param Search		  $searchModel    The model to use for search logs.
	 * @param Guard           $guard          Laravel guard instance, used to get user ID.
	 */
	public function __construct(
		Search $searchModel,
		Guard $guard
	)
	{
		$this->searchModel = $searchModel;
		$this->guard = $guard;
	}

	/**
	 * Find a single searchlog by ID.
	 *
	 * @param string $id The ID of the search to find.
	 *
	 * @return mixed
	 */
	public function find($id)
	{
		$userId = $this->guard->user()->id;
		if($userId <= 0)
		{
			$userId = null;
		}
		return $this->searchModel->where('user_id', $userId)->find($id);
	}

	/**
	 * Create a new searchlog
	 *
	 * @param array $details Details about the searchlog.
	 *
	 * @return mixed
	 */
	public function create(array $details = [])
	{
		$details = array_merge([
			'id' => md5(uniqid(microtime(), true)),
			'keywords' => '',
			'as_topics' => true,
			'user_id' => $this->guard->user()->id,
			'topics' => '',
			'posts' => ''
		], $details);

		if($details['user_id'] < 0)
		{
			$details['user_id'] = null;
		}

		$searchlog = $this->searchModel->create($details);
		return $searchlog;
	}
}

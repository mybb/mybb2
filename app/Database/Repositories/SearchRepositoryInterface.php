<?php
/**
 * Search repository contract.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Repositories;

interface SearchRepositoryInterface
{

	/**
	 * Find a single search log by ID.
	 *
	 * @param string $id The ID of the search log to find.
	 *
	 * @return mixed
	 */
	public function find($id);

	/**
	 * Create a new searchlog
	 *
	 * @param array $details Details about the searchlog.
	 *
	 * @return mixed
	 */
	public function create(array $details = []);

}

<?php

namespace MyBB\Core\Database\Repositories;

use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
	/**
	 * @param int $id
	 *
	 * @return Model
	 */
	public function find($id);
}

<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Repositories;

//use Illuminate\Support\Collection;
use MyBB\Core\Database\Models\WordFilter;

interface WordFilterRepositoryInterface
{
    // TODO: Implement this
	//public function find(int $id) : WordFilter;
	
	public function getAll() : Collection;
}

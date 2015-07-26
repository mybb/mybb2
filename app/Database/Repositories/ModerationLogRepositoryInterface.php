<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Repositories;

use MyBB\Core\Database\Models\ModerationLog;

interface ModerationLogRepositoryInterface extends RepositoryInterface
{
	/**
	 * @param array $attributes
	 *
	 * @return ModerationLog
	 */
	public function create(array $attributes);
}

<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Models;

class ModerationLogSubject extends AbstractCachingModel
{
	/**
	 * @var string
	 */
	protected $table = 'moderation_log_subjects';

	/**
	 * @var array
	 */
	protected $guarded = ['id'];
}

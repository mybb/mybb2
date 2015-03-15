<?php
/**
 * User activity model.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/settings
 * @copyright Copyright (c) 2014, MyBB Group
 * @license   http://www.mybb.com/about/license GNU LESSER GENERAL PUBLIC LICENSE
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\Database\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'user_activity';

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'extra_details' => 'array',
	];

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = [];

	public function user()
	{
		return $this->belongsTo('MyBB\Core\Database\Models\User');
	}

	public function activityHistorable()
	{
		return $this->morphTo(null, 'activity_type', 'activity_id');
	}
}

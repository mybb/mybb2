<?php
/**
 * Like model.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2014, MyBB Group
 * @license   http://www.mybb.com/about/license GNU LESSER GENERAL PUBLIC LICENSE
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\Likes\Database\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int user_id
 * @property string content_type
 * @property int content_id
 */
class Like extends Model
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'likes';

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = [];

	/**
	 * The relations to eager load on every query.
	 *
	 * @var array
	 */
	protected $with = ['user'];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo('MyBB\Core\Database\Models\User');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\MorphTo
	 */
	public function likeable()
	{
		return $this->morphTo(null, 'content_type', 'content_id');
	}
}

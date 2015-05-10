<?php
/**
 * Thread model class.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use McCool\LaravelAutoPresenter\HasPresenter;

class Topic extends Model implements HasPresenter
{
	use SoftDeletes;

	// @codingStandardsIgnoreStart

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var boolean
	 */
	public $timestamps = true;

	// @codingStandardsIgnoreEnd

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'topics';
	/**
	 * The relations to eager load on every query.
	 *
	 * @var array
	 */
	protected $with = [];
	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = [];

	/**
	 * The date attributes.
	 *
	 * @var array
	 */
	protected $dates = ['deleted_at', 'created_at', 'updated_at'];

	/**
	 * Get the presenter class.
	 *
	 * @return string
	 */
	public function getPresenterClass()
	{
		return 'MyBB\Core\Presenters\Topic';
	}

	/**
	 * A topic has many posts.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function posts()
	{
		return $this->hasMany('MyBB\\Core\\Database\\Models\\Post');
	}

	/**
	 * A topic has one poll.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\hasOne
	 */
	public function poll()
	{
		return $this->hasOne('MyBB\\Core\\Database\\Models\\Poll');
	}

	/**
	 * A thread has many contributors (authors of posts belonging to the thread).
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
	 */
	public function contributors()
	{
		return $this->hasManyThrough('MyBB\\Core\\Database\\Models\\User', 'MyBB\\Core\\Database\\Models\\Post');
	}

	/**
	 * A thread belongs to one forum.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function forum()
	{
		return $this->belongsTo('MyBB\\Core\\Database\\Models\\Forum');
	}

	/**
	 * A thread is created by (and belongs to) a user/author.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function author()
	{
		return $this->belongsTo('MyBB\\Core\\Database\\Models\\User', 'user_id');
	}

	// TODO: Other relations? Are the below necessary?
	// TODO: Will probably be quicker to store last post and first post ID than alternatives...

	/**
	 * A thread has a single first post.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function firstPost()
	{
		return $this->hasOne('MyBB\\Core\\Database\\Models\\Post', 'id', 'first_post_id');
	}

	/**
	 * A thread has a single last post.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function lastPost()
	{
		return $this->hasOne('MyBB\\Core\\Database\\Models\\Post', 'id', 'last_post_id');
	}
}

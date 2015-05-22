<?php
/**
 * Forum model class.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Models;

use MyBB\Core\Permissions\Interfaces\InheritPermissionInterface;
use MyBB\Core\Permissions\Traits\InheritPermissionableTrait;
use McCool\LaravelAutoPresenter\HasPresenter;
use MyBB\Core\Database\Collections\TreeCollection;

class Forum extends AbstractCachingModel implements HasPresenter, InheritPermissionInterface
{
	use InheritPermissionableTrait;

	// @codingStandardsIgnoreStart
	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var boolean
	 */
	public $timestamps = false;

	// @codingStandardsIgnoreEnd

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'forums';
	/**
	 * The relations to eager load on every query.
	 *
	 * @var array
	 */
	protected $with = array();
	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['left_id', 'right_id', 'parent_id'];

	/**
	 * Get the presenter class.
	 *
	 * @return string
	 */
	public function getPresenterClass()
	{
		return 'MyBB\Core\Presenters\Forum';
	}

	/**
	 * @return InheritPermissionableTrait
	 */
	public function getParent()
	{
		if ($this->parent_id === null) {
			return null;
		}

		return $this->find($this->parent_id);
	}

	/**
	 * A forum contains many threads.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function topics()
	{
		return $this->hasMany('MyBB\\Core\\Database\\Models\\Topic');
	}

	/**
	 * A forum contains many posts, through its threads.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
	 */
	public function posts()
	{
		return $this->hasManyThrough('MyBB\\Core\\Database\\Models\\Post', 'MyBB\\Core\\Database\\Models\\Topic');
	}

	/**
	 * A forum has a single last post.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function lastPost()
	{
		return $this->hasOne('MyBB\\Core\\Database\\Models\\Post', 'id', 'last_post_id');
	}

	/**
	 * A forum has a single last post author.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function lastPostAuthor()
	{
		return $this->hasOne('MyBB\\Core\\Database\\Models\\User', 'id', 'last_post_user_id');
	}

	/**
	 * Relation to the parent.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function parent()
	{
		return $this->belongsTo(get_class($this), 'parent_id');
	}
	/**
	 * Relation to children.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function children()
	{
		return $this->hasMany(get_class($this), 'parent_id');
	}

	/**
	 * {@inheritdoc}
	 */
	public function newCollection(array $models = array())
	{
		return new TreeCollection($models);
	}
}

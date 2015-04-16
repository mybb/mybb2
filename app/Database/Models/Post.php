<?php
/**
 * Post model class.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use McCool\LaravelAutoPresenter\HasPresenter;
use MyBB\Core\Likes\Traits\LikeableTrait;
use MyBB\Core\Moderation\Moderations\ApprovableInterface;

/**
 * @property int topic_id
 */
class Post extends Model implements HasPresenter, ApprovableInterface
{
	use SoftDeletes;
    use LikeableTrait;

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = true;
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'posts';
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
	protected $guarded = array();

	/**
	 * The date attributes.
	 *
	 * @var array
	 */
	protected $dates = ['deleted_at', 'created_at', 'updated_at'];

	/**
	 * @var array
	 */
	protected $casts = [
		'topic_id' => 'int'
	];

	/**
	 * Get the presenter class.
	 *
	 * @return string
	 */
	public function getPresenterClass()
	{
		return 'MyBB\Core\Presenters\Post';
	}

	/**
	 * A post belongs to a thread.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function topic()
	{
		return $this->belongsTo('MyBB\\Core\\Database\\Models\\Topic')->withTrashed();
	}

	/**
	 * A post is created by (and belongs to) a user/author.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function author()
	{
		return $this->belongsTo('MyBB\\Core\\Database\\Models\\User', 'user_id');
	}

	/**
	 * @return bool|int
	 */
	public function approve()
	{
		return $this->update(['approved' => 1]);
	}

	/**
	 * @return bool|int
	 */
	public function unapprove()
	{
		return $this->update(['approved' => 0]);
	}
}

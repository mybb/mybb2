<?php namespace MyBB\Core\Database\Models;

use Illuminate\Database\Eloquent\Model;
use McCool\LaravelAutoPresenter\HasPresenter;


class Poll extends Model implements HasPresenter
{
	/**
	 * Indicates if the IDs are auto-incrementing.
	 *
	 * @var bool
	 */
	public $incrementing = true;
	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = true;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'question',
		'user_id',
		'topic_id',
		'num_options',
		'options',
		'is_closed',
		'is_public',
		'is_multiple',
		'max_options',
		'end_at'
	];
	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['user_id'];
	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'id';
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'polls';
	/**
	 * Get the presenter class.
	 *
	 * @return string
	 */
	public function getPresenterClass()
	{
		return \MyBB\Core\Presenters\Poll::class; // TODO: Are we using PHP 5.5 as minimum? If so, this is fine...
	}

	/**
	 * A poll belongs to a topic.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function topic()
	{
		return $this->belongsTo('MyBB\\Core\\Database\\Models\\Topic')->withTrashed();
	}

	/**
	 * A poll is created by (and belongs to) a user/author.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function author()
	{
		return $this->belongsTo('MyBB\\Core\\Database\\Models\\User', 'user_id');
	}

	/**
	 * A thread has many votes.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function votes()
	{
		return $this->hasMany('MyBB\\Core\\Database\\Models\\PollVote');
	}
}

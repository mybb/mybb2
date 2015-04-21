<?php

namespace MyBB\Core\Database\Models;

use Illuminate\Database\Eloquent\Model;
use McCool\LaravelAutoPresenter\HasPresenter;

class Poll extends Model implements HasPresenter
{
	// @codingStandardsIgnoreStart

	/**
	 * Indicates if the IDs are auto-incrementing.
	 *
	 * @var boolean
	 */
	public $incrementing = true;
	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var boolean
	 */
	public $timestamps = true;

	// @codingStandardsIgnoreEnd

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
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'is_closed' => 'boolean',
		'is_public' => 'boolean',
		'is_multiple' => 'boolean',
		'options' => 'array'
	];
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
		return 'MyBB\\Core\\Presenters\\Poll';
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
	 * A poll has many votes.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function votes()
	{
		return $this->hasMany('MyBB\\Core\\Database\\Models\\PollVote');
	}
}

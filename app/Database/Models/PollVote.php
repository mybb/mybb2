<?php

namespace MyBB\Core\Database\Models;

use Illuminate\Database\Eloquent\Model;

class PollVote extends Model
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
		'poll_id',
		'user_id',
		'vote'
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
	protected $table = 'poll_votes';

	/**
	 * A vote belongs to a poll.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function poll()
	{
		return $this->belongsTo('MyBB\\Core\\Database\\Models\\Poll');
	}

	/**
	 * A vote is created by (and belongs to) a user/author.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function author()
	{
		return $this->belongsTo('MyBB\\Core\\Database\\Models\\User', 'user_id');
	}
}

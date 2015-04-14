<?php

namespace MyBB\Core\Database\Models;

use Carbon\Carbon;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use McCool\LaravelAutoPresenter\HasPresenter;

/**
 * @property int          id
 * @property int          conversation_id
 * @property int          author_id
 * @property string       message
 * @property Carbon       created_at
 * @property Carbon       updated_at
 * @property Conversation conversation
 * @property User         author
 */
class ConversationMessage extends Model implements HasPresenter
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'conversations_messages';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'message',
		'author_id'
	];

	/**
	 * The relations to eager load on every query.
	 *
	 * @var array
	 */
	protected $with = [
		'author'
	];

	/**
	 * Get the presenter class.
	 *
	 * @return string
	 */
	public function getPresenterClass()
	{
		return 'MyBB\Core\Presenters\ConversationMessage';
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function conversation()
	{
		return $this->belongsTo('MyBB\Core\Database\Models\Conversation');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function author()
	{
		return $this->belongsTo('MyBB\\Core\\Database\\Models\\User', 'author_id');
	}
}

<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use McCool\LaravelAutoPresenter\HasPresenter;

/**
 * @property int                 id
 * @property string              title
 * @property int|null            last_message_id
 * @property Collection          messages
 * @property ConversationMessage lastMessage
 * @property Collection          participants
 */
class Conversation extends AbstractCachingModel implements HasPresenter
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'conversations';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'title',
		'last_message_id'
	];

	/**
	 * The relations to eager load on every query.
	 *
	 * @var array
	 */
	protected $with = [
		'messages',
		'lastMessage'
	];

	// @codingStandardsIgnoreStart

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var boolean
	 */
	public $timestamps = false;

	// @codingStandardsIgnoreEnd

	/**
	 * Get the presenter class.
	 *
	 * @return string
	 */
	public function getPresenterClass()
	{
		return 'MyBB\Core\Presenters\Conversation';
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function messages()
	{
		return $this->hasMany('MyBB\\Core\\Database\\Models\\ConversationMessage');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function lastMessage()
	{
		return $this->hasOne('MyBB\\Core\\Database\\Models\\ConversationMessage', 'id', 'last_message_id');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function participants()
	{
		return $this->belongsToMany('MyBB\\Core\\Database\\Models\\User', 'conversation_users')
			->withPivot('last_read', 'has_left', 'ignores');
	}
}

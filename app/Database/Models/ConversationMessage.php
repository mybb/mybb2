<?php

namespace MyBB\Core\Database\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use McCool\LaravelAutoPresenter\HasPresenter;
use MyBB\Auth\Authenticatable;
use MyBB\Auth\Contracts\UserContract as AuthenticatableContract;
use MyBB\Core\Permissions\Interfaces\PermissionInterface;
use MyBB\Core\Permissions\Traits\Permissionable;

/**
 * @property string id
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

	public function conversation()
	{
		return $this->belongsTo('MyBB\Core\Database\Models\Conversation');
	}

	public function author()
	{
		return $this->belongsTo('MyBB\\Core\\Database\\Models\\User', 'author_id');
	}
}

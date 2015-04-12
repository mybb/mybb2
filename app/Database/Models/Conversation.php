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
class Conversation extends Model implements HasPresenter
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
	];

	/**
	 * Get the presenter class.
	 *
	 * @return string
	 */
	public function getPresenterClass()
	{
		return 'MyBB\Core\Presenters\Conversation';
	}

	public function messages()
	{
		return $this->hasMany('MyBB\Core\Database\Models\ConversationMessage');
	}

	public function participants()
	{
		return $this->belongsToMany('MyBB\Core\Database\Models\User')->withPivot('last_read', 'has_left', 'ignores');
	}
}

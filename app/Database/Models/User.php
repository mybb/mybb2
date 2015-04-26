<?php

namespace MyBB\Core\Database\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use McCool\LaravelAutoPresenter\HasPresenter;
use MyBB\Auth\Authenticatable;
use MyBB\Auth\Contracts\UserContract as AuthenticatableContract;
use MyBB\Core\Permissions\Interfaces\PermissionInterface;
use MyBB\Core\Permissions\Traits\PermissionableTrait;

/**
 * @property string id
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract, HasPresenter, PermissionInterface
{
	use Authenticatable;
	use CanResetPassword;
	use PermissionableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name',
		'email',
		'password',
		'role_id',
		'avatar',
		'dob',
		'usertitle',
		'last_visit',
		'last_page',
		'num_posts',
		'num_topics'
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'num_likes_made' => 'int',
	];

	/**
	 * Cache variable for the display role
	 *
	 * @var Role
	 */
	private $displayRole;

	/**
	 * Get the presenter class.
	 *
	 * @return string
	 */
	public function getPresenterClass()
	{
		return 'MyBB\Core\Presenters\User';
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return (int)$this->id;
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function roles()
	{
		return $this->belongsToMany('MyBB\\Core\\Database\\Models\\Role')->withPivot('is_display');
	}

	/**
	 * @return Role
	 */
	public function displayRole()
	{
		if ($this->displayRole == null) {
			// Do we have a guest?
			if ($this->id <= 0) {
				$this->displayRole = Role::where('role_slug', 'guest')->first();
			} else {
				$this->displayRole = $this->roles->whereLoose('pivot.is_display', true)->first();
			}
		}

		return $this->displayRole;
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function activity()
	{
		return $this->hasMany('MyBB\\Core\\Database\\Models\\UserActivity');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function conversations()
	{
		return $this->belongsToMany('MyBB\\Core\\Database\\Models\\Conversation', 'conversation_users')->withPivot(
			'last_read',
			'has_left',
			'ignores'
		)
			->orderBy('last_message_id', 'desc')
			->where('conversation_users.has_left', false)
			->where('conversation_users.ignores', false);
	}
}

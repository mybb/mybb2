<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use McCool\LaravelAutoPresenter\HasPresenter;
use MyBB\Auth\Authenticatable;
use MyBB\Auth\Contracts\UserContract as AuthenticatableContract;
use MyBB\Core\Permissions\Interfaces\PermissionInterface;
use MyBB\Core\Permissions\Traits\PermissionableTrait;
use MyBB\Core\UserActivity\Contracts\ActivityStoreableInterface;
use MyBB\Core\UserActivity\Traits\UserActivityTrait;

/**
 * @property string id
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract, HasPresenter, PermissionInterface, ActivityStoreableInterface
{
	use Authenticatable;
	use CanResetPassword;
	use PermissionableTrait;
	use UserActivityTrait;

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

	/**
	 * Check whether this activity entry should be saved.
	 *
	 * @return bool
	 */
	public function checkStoreable()
	{
		return true;
	}

	/**
	 * Get the ID of the model.
	 *
	 * @return int
	 */
	public function getContentId()
	{
		return $this->id;
	}

	/**
	 * Get extra details about a model.
	 *
	 * @return array The extra details to store.
	 */
	public function getExtraDetails()
	{
		return [];
	}

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		// TODO: Implement getAuthIdentifier() method.
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		// TODO: Implement getAuthPassword() method.
	}

	/**
	 * Get the token value for the "remember me" session.
	 *
	 * @return string
	 */
	public function getRememberToken()
	{
		// TODO: Implement getRememberToken() method.
	}

	/**
	 * Set the token value for the "remember me" session.
	 *
	 * @param  string $value
	 *
	 * @return void
	 */
	public function setRememberToken($value)
	{
		// TODO: Implement setRememberToken() method.
	}

	/**
	 * Get the column name for the "remember me" token.
	 *
	 * @return string
	 */
	public function getRememberTokenName()
	{
		// TODO: Implement getRememberTokenName() method.
	}

	/**
	 * Get the e-mail address where password reset links are sent.
	 *
	 * @return string
	 */
	public function getEmailForPasswordReset()
	{
		// TODO: Implement getEmailForPasswordReset() method.
	}

	/**
	 * @return string
	 */
	public static function getViewablePermission()
	{
		// TODO: Implement getViewablePermission() method.
	}

	/**
	 * Get the username for the user
	 *
	 * @return string
	 */
	public function getUsername()
	{
		// TODO: Implement getUsername() method.
	}

	/**
	 * Get the salt for the user
	 *
	 * @return string
	 */
	public function getSalt()
	{
		// TODO: Implement getSalt() method.
	}

	/**
	 * Get the hasher type for the user
	 *
	 * @return string
	 */
	public function getHasher()
	{
		// TODO: Implement getHasher() method.
	}}

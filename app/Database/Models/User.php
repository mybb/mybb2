<?php

namespace MyBB\Core\Database\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use McCool\LaravelAutoPresenter\HasPresenter;
use MyBB\Auth\Authenticatable;
use MyBB\Auth\Contracts\UserContract as AuthenticatableContract;
use MyBB\Core\Services\PermissionChecker;
use MyBB\Core\Traits\PermissionHandler;

/**
 * @property string id
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract, HasPresenter
{

	use Authenticatable, CanResetPassword;

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

	public function roles()
	{
		return $this->belongsToMany('MyBB\Core\Database\Models\Role')->withPivot('is_display');
	}

	public function displayRole()
	{
		// TODO: Cache this?
		return $this->roles->where('pivot.is_display', 1)->first();
	}

	public function activity()
	{
		return $this->hasMany('MyBB\Core\Database\Models\UserActivity');
	}

	public function hasPermission($permission)
	{
		if (is_array($permission)) {
			foreach ($permission as $perm) {
				$hasPermission = $this->hasPermission($perm);

				if ($hasPermission != PermissionChecker::YES) {
					return false;
				}
			}

			return true;
		}

		// Handle special cases where no role has been set
		$roles = $this->roles;
		if ($roles->count() == 0) {
			if ($this->exists) {
				// User saved? Something is wrong, attach the registered role
				$registeredRole = Role::where('role_slug', '=', 'user')->first();
				$this->roles()->attach($registeredRole->id, ['is_display' => 1]);
				$roles = [$registeredRole];
			} else {
				// Guest
				$guestRole = Role::where('role_slug', '=', 'guest')->first();
				$roles = [$guestRole];
			}
		}

		// TODO: Cache this foreach?
		$isAllowed = false;
		foreach ($roles as $role) {
			$hasPermission = PermissionChecker::hasPermission($role, $permission);

			if ($hasPermission == PermissionChecker::NEVER) {
				return false;
			} elseif ($hasPermission == PermissionChecker::YES) {
				$isAllowed = true;
			}
		}

		return $isAllowed;
	}
}

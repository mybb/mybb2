<?php

namespace MyBB\Core\Database\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use McCool\LaravelAutoPresenter\HasPresenter;
use MyBB\Auth\Authenticatable;
use MyBB\Auth\Contracts\UserContract as AuthenticatableContract;
use MyBB\Core\Traits\Permissionable;

/**
 * @property string id
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract, HasPresenter
{
	use Authenticatable, CanResetPassword, Permissionable;

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

	public function roles()
	{
		return $this->belongsToMany('MyBB\Core\Database\Models\Role')->withPivot('is_display');
	}

	public function displayRole()
	{
		if($this->displayRole == null) {
			$this->displayRole = $this->roles->where('pivot.is_display', 1)->first();
		}
		return $this->displayRole;
	}

	public function activity()
	{
		return $this->hasMany('MyBB\Core\Database\Models\UserActivity');
	}
}

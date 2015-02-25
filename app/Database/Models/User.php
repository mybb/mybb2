<?php

namespace MyBB\Core\Database\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use MyBB\Auth\Authenticatable;
use MyBB\Auth\Contracts\UserContract as AuthenticatableContract;
use MyBB\Core\Traits\PermissionHandler;
use McCool\LaravelAutoPresenter\HasPresenter;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract, HasPresenter
{

	use Authenticatable, CanResetPassword;

	use PermissionHandler;

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
	protected $fillable = ['name', 'email', 'password', 'role_id', 'dob', 'usertitle'];

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
		return \MyBB\Core\Presenters\User::class; // TODO: Are we using PHP 5.5 as minimum? If so, this is fine...
	}


	public function role()
	{
		return $this->hasOne('MyBB\Core\Database\Models\Role', 'id', 'role_id');
	}

	public function settings()
	{
		return $this->hasOne('MyBB\Core\Database\Models\UserSettings');
	}
}

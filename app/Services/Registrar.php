<?php namespace MyBB\Core\Services;

use DB;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;
use MyBB\Core\Database\Models\Role;
use MyBB\Core\Database\Models\User;
use Validator;

class Registrar implements RegistrarContract
{

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array $data
	 *
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data)
	{
		return Validator::make($data, [
			'name' => 'required|max:255|unique:users',
			'email' => 'required|email|max:255|unique:users',
			'password' => 'required|confirmed|min:6',
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array $data
	 *
	 * @return User
	 */
	public function create(array $data)
	{
		$user = User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => bcrypt($data['password']),
		]);

		$user->roles()->attach(Role::where('role_slug', '=', 'user')->pluck('id'), ['is_display' => true]);

		return $user;
	}

}

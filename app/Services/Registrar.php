<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Services;

use Illuminate\Contracts\Auth\Registrar as RegistrarContract;
use Illuminate\Validation\Factory;
use MyBB\Core\Database\Models\Role;
use MyBB\Core\Database\Models\User;

class Registrar implements RegistrarContract
{
	/**
	 * @var Factory
	 */
	private $validator;

	/**
	 * @param Factory $validator
	 */
	public function __construct(Factory $validator)
	{
		$this->validator = $validator;
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array $data
	 *
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data)
	{
		return $this->validator->make($data, [
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

		$user->roles()->attach(Role::whereSlug('user')->id, ['is_display' => true]);

		return $user;
	}
}

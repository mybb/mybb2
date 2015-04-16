<?php
/**
 * Topic create request.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Http\Requests\Conversations;

use MyBB\Auth\Contracts\Guard;
use MyBB\Core\Http\Requests\Request;
use MyBB\Core\Permissions\PermissionChecker;

class ParticipantRequest extends Request
{
	/** @var Guard $guard */
	private $guard;

	/** @var PermissionChecker $permissionChecker */
	private $permissionChecker;

	/**
	 * @param Guard             $guard
	 * @param PermissionChecker $permissionChecker
	 */
	public function __construct(Guard $guard, PermissionChecker $permissionChecker)
	{
		$this->guard = $guard;
		$this->permissionChecker = $permissionChecker;
	}

	/**
	 * @return array
	 */
	public function rules()
	{
		return [
			'participants' => 'required', // TODO: validate the names
		];
	}

	/**
	 * @return bool
	 */
	public function authorize()
	{
		//return $this->guard->check();
		return true; // TODO: In dev return, needs replacing for later...
	}
}

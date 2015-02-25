<?php
/**
 * User presenter class.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Presenters;

use McCool\LaravelAutoPresenter\BasePresenter;
use MyBB\Core\Database\Models\User as UserModel;

class User extends BasePresenter
{
	/** @var UserModel $wrappedObject */

	/**
	 * @param UserModel $resource The user being wrapped by this presenter.
	 */
	public function __construct(UserModel $resource)
	{
		$this->wrappedObject = $resource;
	}

	public function styled_name()
	{
		if($this->wrappedObject->role->role_username_style)
		{
			return str_replace(':user', $this->wrappedObject->name, $this->wrappedObject->role->role_username_style);
		}
		return $this->wrappedObject->name;
	}
}

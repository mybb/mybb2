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

	public function avatar()
	{
		$avatar = $this->wrappedObject->avatar;

		// Empty? Default avatar
		if(empty($avatar))
		{
			return asset('images/avatar.png');
		}
		// Link? Nice!
		elseif(filter_var($avatar, FILTER_VALIDATE_URL) !== false)
		{
			return $avatar;
		}
		// Email? Set up Gravatar
		elseif(filter_var($avatar, FILTER_VALIDATE_EMAIL) !== false)
		{
			// TODO: Replace with euans package
			return "http://gravatar.com/avatar/".md5(strtolower(trim($avatar)));
		}
		// File?
		elseif(file_exists(public_path("uploads/avatars/{$avatar}")))
		{
			return asset("uploads/avatars/{$avatar}");
		}
		// Nothing?
		else
		{
			return asset('images/avatar.png');
		}
	}

	public function avatar_link()
	{
		$avatar = $this->wrappedObject->avatar;

		// If we have an email or link we'll return it - otherwise nothing
		// Link? Nice!
		if(filter_var($avatar, FILTER_VALIDATE_URL) !== false || filter_var($avatar, FILTER_VALIDATE_EMAIL) !== false)
		{
			return $avatar;
		}

		return '';
	}
}

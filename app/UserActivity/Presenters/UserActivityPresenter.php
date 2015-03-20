<?php
/**
 * User activity presenter.
 *
 * Formats a user activity instance for output.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/settings
 * @copyright Copyright (c) 2014, MyBB Group
 * @license   http://www.mybb.com/about/license GNU LESSER GENERAL PUBLIC LICENSE
 * @link      http://www.mybb.com
 */

namespace Mybb\Core\UserActivity\Presenters;

use Illuminate\Translation\Translator;
use McCool\LaravelAutoPresenter\BasePresenter;
use MyBB\Core\UserActivity\Database\Models\UserActivity;

class UserActivityPresenter extends BasePresenter
{
	/**
	 * @var Translator $translator
	 */
	private $translator;

	/**
	 * @param UserActivity $resource
	 * @param Translator   $lang
	 */
	public function __construct(UserActivity $resource, Translator $lang)
	{
		$this->wrappedObject = $resource;
		$this->translator = $lang;
	}

	/**
	 * Get the activity string for this user activity item.
	 *
	 * @return string
	 */
	public function activity()
	{
		// TODO: Return string representation of the activity, with links to view the activity.
		return "";
	}
}

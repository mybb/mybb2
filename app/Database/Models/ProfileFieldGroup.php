<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Models;

use Illuminate\Database\Eloquent\Model;
use McCool\LaravelAutoPresenter\HasPresenter;

/**
 * @property int id
 */
class ProfileFieldGroup extends Model implements HasPresenter
{
	const ABOUT_YOU = 'about-you';
	const CONTACT_DETAILS = 'contact-details';

	/**
	 * @var string
	 */
	protected $table = 'profile_field_groups';
	/**
	 * @var array
	 */
	protected $dates = ['created_at', 'updated_at'];
	/**
	 * @var array
	 */
	protected $guarded = ['id'];
	/**
	 * @var array
	 */
	protected $casts = [
		'id' => 'int'
	];
	/**
	 * @var array
	 */
	protected $with = [
		'getProfileFields'
	];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function getProfileFields()
	{
		return $this->hasMany('MyBB\Core\Database\Models\ProfileField', 'profile_field_group_id');
	}

	/**
	 * Get the presenter class.
	 *
	 * @return string
	 */
	public function getPresenterClass()
	{
		return 'MyBB\Core\Presenters\ProfileFieldGroupPresenter';
	}
}

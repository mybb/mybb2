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
 * @property int    id
 * @property int    profile_field_group_id
 * @property string validation_rules
 * @property string name
 */
class ProfileField extends AbstractCachingModel implements HasPresenter
{
	/**
	 * @var string
	 */
	protected $table = 'profile_fields';
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
		'id' => 'int',
		'profile_field_group_id' => 'int'
	];

	/**
	 * Get the presenter class.
	 *
	 * @return string
	 */
	public function getPresenterClass()
	{
		return 'MyBB\Core\Presenters\ProfileField';
	}
}

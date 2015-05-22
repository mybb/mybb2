<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string value
 */
class UserProfileField extends AbstractCachingModel
{
	/**
	 * @var string
	 */
	protected $table = 'user_profile_fields';
	/**
	 * @var array
	 */
	protected $dates = ['created_at', 'updated_at'];
	/**
	 * @var array
	 */
	protected $guarded = ['id'];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function getProfileField()
	{
		return $this->belongsTo('MyBB\Core\Database\Models\ProfileField', 'profile_field_id', null, 'profileField');
	}

	/**
	 * @param string $value
	 */
	public function setValue($value)
	{
		$this->value = $value;
	}

	/**
	 * @return string
	 */
	public function getValue()
	{
		return $this->value;
	}
}

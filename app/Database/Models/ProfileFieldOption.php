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
 * @property string name
 * @property string value
 */
class ProfileFieldOption extends Model
{
	/**
	 * @var string
	 */
	protected $table = 'profile_field_options';
	/**
	 * @var array
	 */
	protected $dates = ['created_at', 'updated_at'];
	/**
	 * @var array
	 */
	protected $guarded = ['id'];

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getValue()
	{
		return $this->value;
	}
}

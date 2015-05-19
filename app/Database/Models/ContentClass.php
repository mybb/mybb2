<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use McCool\LaravelAutoPresenter\HasPresenter;
use MyBB\Auth\Authenticatable;
use MyBB\Auth\Contracts\UserContract as AuthenticatableContract;
use MyBB\Core\Traits\Permissionable;

/**
 * @property string class
 * @property string content
 */
class ContentClass extends AbstractCachingModel
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'content_class';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'content';

	// @codingStandardsIgnoreStart

	/**
	 * Indicates if the IDs are auto-incrementing.
	 *
	 * @var boolean
	 */
	public $incrementing = false;

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var boolean
	 */
	public $timestamps = false;

	// @codingStandardsIgnoreEnd

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'content',
		'class'
	];

	/**
	 * Shortcut for "ContentClass::find($content)->getConcreteClass();"
	 *
	 * @param string $content
	 *
	 * @return mixed|null Return null if no class is found, otherwise a represantion of the registered class
	 */
	public static function getClass($content)
	{
		$model = static::find($content);

		if ($model == null) {
			return null;
		}

		return $model->getConcreteClass();
	}

	/**
	 * Return a representation of the class for this content
	 *
	 * @return mixed
	 */
	public function getConcreteClass()
	{
		return app()->make($this->class);
	}
}

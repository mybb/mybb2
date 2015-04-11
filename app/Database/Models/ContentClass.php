<?php

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
class ContentClass extends Model
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

	/**
	 * Indicates if the IDs are auto-incrementing.
	 *
	 * @var bool
	 */
	public $incrementing = false;

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'content',
		'class'
	];

	/** @var array */
	private static $models;

	/**
	 * {@inheritdoc}
	 */
	public static function find($id, $columns = array('*'))
	{
		if ($columns != array('*')) {
			return parent::find($id, $columns);
		}

		if (!isset(static::$models[$id])) {
			static::$models[$id] = parent::find($id);
		}

		return static::$models[$id];
	}

	/**
	 * Shortcut for "ContentClass::find($content)->getConcreteClass();"
	 *
	 * @param $content
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

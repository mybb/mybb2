<?php

namespace MyBB\Core\Database\Models;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractCachingModel extends Model
{
	/** @var array */
	protected static $models;

	/**
	 * {@inheritdoc}
	 */
	public function save(array $options = array())
	{
		$saved = parent::save($options);

		if ($saved) {
			static::$models[$this->getKey()] = $this;
		}

		return $saved;
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete()
	{
		parent::delete();
		unset(static::$models[$this->getKey()]);
	}

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

}

<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Models;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractCachingModel extends Model
{
	/**
	 * @var array
	 */
	protected static $models;

	/**
	 * {@inheritdoc}
	 */
	public function save(array $options = array())
	{
		$saved = parent::save($options);

		if ($saved) {
			static::$models[get_class($this)][$this->getKey()] = $this;
		}

		return $saved;
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete()
	{
		parent::delete();
		unset(static::$models[get_class($this)][$this->getKey()]);
	}

	/**
	 * {@inheritdoc}
	 */
	public static function find($id, $columns = array('*'))
	{
		if ($columns != array('*')) {
			return parent::find($id, $columns);
		}

		if (!isset(static::$models[get_called_class()][$id])) {
			static::$models[get_called_class()][$id] = parent::find($id);
		}

		return static::$models[get_called_class()][$id];
	}
}

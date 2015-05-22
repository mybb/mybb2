<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Models;

class Role extends AbstractCachingModel
{
	/**
	 * @var array
	 */
	protected static $slugCache;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'roles';

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function users()
	{
		return $this->belongsToMany('MyBB\Core\Database\Models\User');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function permissions()
	{
		return $this->belongsToMany('MyBB\Core\Database\Models\Permission');
	}

	/**
	 * @param string $slug
	 *
	 * @return Role
	 */
	public static function whereSlug($slug)
	{
		if (!isset(static::$slugCache[$slug])) {
			static::$slugCache[$slug] = static::where('role_slug', '=', $slug)->first();
		}

		return static::$slugCache[$slug];
	}

	/**
	 * {@inheritdoc}
	 */
	public function save(array $options = array())
	{
		$saved = parent::save($options);

		if ($saved) {
			static::$slugCache[$this->role_slug] = $this;
		}

		return $saved;
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete()
	{
		parent::delete();
		unset(static::$slugCache[$this->role_slug]);
	}

	/**
	 * {@inheritdoc}
	 */
	public static function find($id, $columns = array('*'))
	{
		$model = parent::find($id, $columns);

		if ($columns == array('*')) {
			static::$slugCache[$model->role_slug] = $model;
		}

		return $model;
	}
}

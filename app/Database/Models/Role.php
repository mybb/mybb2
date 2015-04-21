<?php namespace MyBB\Core\Database\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

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
}

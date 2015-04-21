<?php namespace MyBB\Core\Database\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'permissions';


	public function roles()
	{
		$this->belongsToMany('MyBB\Core\Database\Models\Role');
	}
}

<?php namespace MyBB\Core\Database\Models;

use Illuminate\Database\Eloquent\Model;


class RolePermission extends Model
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'role_permissions';


	public function permissions()
	{
		return $this->hasMany('MyBB\Core\Databases\Models\Permission');
	}
}

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
 * Class Permission
 *
 * @package MyBB\Core\Database\Models
 *
 * @property int $id
 * @property string permission_display
 * @property string $slug
 */
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
        $this->belongsToMany(Role::class);
    }
}

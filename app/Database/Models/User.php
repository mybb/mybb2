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
use Illuminate\Foundation\Auth\User as Authenticatable;
use McCool\LaravelAutoPresenter\HasPresenter;
use MyBB\Auth\MyBBUserContract;
use MyBB\Core\Permissions\Interfaces\PermissionInterface;
use MyBB\Core\Permissions\Traits\PermissionableTrait;
use MyBB\Core\Presenters\UserPresenter;

/**
 * @property string id
 */
class User extends Authenticatable implements MyBBUserContract, CanResetPasswordContract, HasPresenter, PermissionInterface
{
    use CanResetPassword;
    use PermissionableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'avatar',
        'dob',
        'usertitle',
        'last_visit',
        'last_page',
        'num_posts',
        'num_topics',
        'warn_points',
        'warned',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'num_likes_made' => 'int',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
	// @codingStandardsIgnoreStart
	public $timestamps = false;
	// @codingStandardsIgnoreEnd
    /**
     * Cache variable for the display role
     *
     * @var Role
     */
    private $displayRole;

    /**
     * Get the presenter class.
     *
     * @return string
     */
    public function getPresenterClass()
    {
        return UserPresenter::class;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return (int)$this->id;
    }

    /**
     * Find a model by its primary key.
     *
     * @param  mixed $id
     * @param  array $columns
     *
     * @return \Illuminate\Support\Collection|static|null
     */
    public static function find($id, $columns = ['*'])
    {
        return static::query()->find($id, $columns);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(\MyBB\Core\Database\Models\Role::class)->withPivot('is_display');
    }

    /**
     * @return Role
     */
    public function displayRole()
    {
        if ($this->displayRole == null) {
            // Do we have a guest?
            if ($this->id <= 0) {
                $this->displayRole = Role::where('role_slug', 'guest')->first();
            } else {
                $this->displayRole = $this->roles->whereLoose('pivot.is_display', true)->first();
            }
        }

        return $this->displayRole;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activity()
    {
        return $this->hasMany(\MyBB\Core\Database\Models\UserActivity::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function conversations()
    {
        return $this->belongsToMany(\MyBB\Core\Database\Models\Conversation::class, 'conversation_users')->withPivot(
            'last_read',
            'has_left',
            'ignores'
        )
            ->orderBy('last_message_id', 'desc')
            ->where('conversation_users.has_left', false)
            ->where('conversation_users.ignores', false);
    }

    /**
     * Get the username of the user.
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->name;
    }

    /**
     * Get the salt for the user.
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Get the type of hasher for the user.
     *
     * Defaults to "core", which sues the built in Laravel hasher (Bcrypt).
     *
     * @return string
     */
    public function getHasher()
    {
        return $this->hasher;
    }

    /**
     * Define the relationship with the user's access tokens.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accessTokens()
    {
        return $this->hasMany('MyBB\Core\Database\Models\AccessToken');
    }
}

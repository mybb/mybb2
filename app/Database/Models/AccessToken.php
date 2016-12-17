<?php

/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Models;

use MyBB\Core\Database\AbstractModel;

/**
 * @property string $id
 * @property int $user_id
 * @property int $last_activity
 * @property int $lifetime
 * @property \MyBB\Core\Database\Models\User|null $user
 */
class AccessToken extends AbstractModel
{
    /**
     * {@inheritdoc}
     */
    protected $table = 'access_tokens';

    /**
     * Use a custom primary key for this model.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Generate an access token for the specified user.
     *
     * @param int $userId
     * @param int $lifetime
     * @return static
     */
    public static function generate($userId, $lifetime = 3600)
    {
        $token = new static;

        $token->id = str_random(40);
        $token->user_id = $userId;
        $token->last_activity = time();
        $token->lifetime = $lifetime;

        return $token;
    }

    public function touch()
    {
        $this->last_activity = time();

        return $this->save();
    }

    /**
     * Define the relationship with the owner of this access token.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('MyBB\Core\Database\Models\User');
    }
}

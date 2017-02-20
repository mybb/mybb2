<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Models;

use Illuminate\Database\Eloquent\Model;
use McCool\LaravelAutoPresenter\HasPresenter;

class Warning extends Model implements HasPresenter
{
    /**
     * @var string
     */
    protected $table = 'warnings';

    /**
     * @var array
     */
    protected $dates = ['expires_at', 'revoked_at'];

    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get the presenter class.
     *
     * @return string
     */
    public function getPresenterClass() : string
    {
        return \MyBB\Core\Presenters\WarningsPresenter::class;
    }

    /**
     * A warning is issued by (and belongs to) a user/moderator.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function issuedBy()
    {
        return $this->belongsTo(\MyBB\Core\Database\Models\User::class, 'issued_by');
    }

    /**
     * A warning is revoked by (and belongs to) a user/moderator.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function revokedBy()
    {
        return $this->belongsTo(\MyBB\Core\Database\Models\User::class, 'revoked_by');
    }

    /**
     * A warning is owned by (and belongs to) a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(\MyBB\Core\Database\Models\User::class, 'user_id');
    }
}

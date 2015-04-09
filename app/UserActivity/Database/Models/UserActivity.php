<?php
/**
 * User activity model.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2014, MyBB Group
 * @license   http://www.mybb.com/about/license GNU LESSER GENERAL PUBLIC LICENSE
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\UserActivity\Database\Models;

use Illuminate\Database\Eloquent\Model;
use McCool\LaravelAutoPresenter\HasPresenter;

/**
 * @property int            id
 * @property int            user_id
 * @property string         activity_type
 * @property int            activity_id
 * @property array          extra_details
 * @property \Carbon\Carbon created_at
 * @property \Carbon\Carbon updated_at
 */
class UserActivity extends Model implements HasPresenter
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_activity';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'extra_details' => 'array',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['user', 'activityHistorable'];

    public function user()
    {
        return $this->belongsTo('MyBB\Core\Database\Models\User');
    }

    public function activityHistorable()
    {
        return $this->morphTo(null, 'activity_type', 'activity_id');
    }

    /**
     * Get the presenter class.
     *
     * @return string
     */
    public function getPresenterClass()
    {
        return 'MyBB\Core\UserActivity\Presenters\UserActivityPresenter';
    }
}

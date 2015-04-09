<?php
/**
 * Trait to be used by models that should store user activity on creation.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2014, MyBB Group
 * @license   http://www.mybb.com/about/license GNU LESSER GENERAL PUBLIC LICENSE
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\UserActivity\Traits;

use Illuminate\Database\Eloquent\Model;

trait UserActivityTrait
{
    /**
     * Boot the user activity trait for a model.
     *
     * @return void
     */
    public static function bootUserActivityTrait()
    {
        static::observe('MyBB\Core\UserActivity\Observers\EloquentObserver');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activityHistory()
    {
        return $this->morphMany(
            'MyBB\Core\UserActivity\Database\Models\UserActivity',
            'activityHistorable',
            'activity_type',
            'activity_id'
        );
    }
}

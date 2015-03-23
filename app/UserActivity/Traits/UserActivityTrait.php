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
        static::registerModelEvent(
            'created',
            function (Model $model) {
                if (($user = \Auth::user()) !== null && $user->getAuthIdentifier() !== null) {
                    $model->activityHistory()->create(
                        [
                            'user_id'       => $user->getAuthIdentifier(),
                            'extra_details' => static::getActivityDetails($model),
                        ]
                    );
                }
            }
        );
    }

    /**
     * Get extra details about a model.
     *
     * @param Model $model The model being created and stored as activity.
     *
     * @return array The extra details to store.
     */
    public static function getActivityDetails(Model $model)
    {
        return [];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activityHistory()
    {
        return $this->morphMany(
            'MyBB\Core\Database\Models\UserActivity',
            'activityHistorable',
            'activity_type',
            'activity_id'
        );
    }
}

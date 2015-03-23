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

class UserActivity extends Model
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
     * Get the ID of the activity entry.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the ID of the user associated with this activity entry.
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set the ID of the user associated with this activity entry.
     *
     * @param int $userId The ID of the user.
     *
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->user_id = (int) $userId;

        return $this;
    }

    /**
     * Get the user associated with this activity entry.
     *
     * @return \MyBB\Core\Database\Models\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get the activity type associated with this activity.
     *
     * @return string
     */
    public function getActivityType()
    {
        return $this->activity_type;
    }

    /**
     * Set the activity type associated with this activity.
     *
     * @param string $activityType The activity type.
     *
     * @return $this
     */
    public function setActivityType($activityType)
    {
        $this->activityType = (string) $activityType;

        return $this;
    }

    /**
     * Get the ID of the content associated with this activity entry.
     *
     * @return int
     */
    public function getActivityId()
    {
        return $this->activity_id;
    }

    /**
     * Set the ID of the content associated with this activity entry.
     *
     * @param int $id The ID of the content.
     *
     * @return $this
     */
    public function setActivityId($id)
    {
        $this->activity_id = (int) $id;

        return $this;
    }

    /**
     * Get any extra details associated with this activity entry.
     *
     * @return array
     */
    public function getExtraDetails()
    {
        return $this->extra_details;
    }

    /**
     * Set any extra details associated with this activity entry.
     *
     * @param array $details Extra details about the entry.
     *
     * @return $this
     */
    public function setExtraDetails(array $details)
    {
        $this->extra_details = $details;


        return $this;
    }

    /**
     * Get the date/time this activity entry was created at.
     *
     * @return \Carbon\Carbon
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Get the date/time this activity entry was last updated at.
     *
     * @return \Carbon\Carbon
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
}

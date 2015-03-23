<?php
/**
 * Post activity renderer.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2014, MyBB Group
 * @license   http://www.mybb.com/about/license GNU LESSER GENERAL PUBLIC LICENSE
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\UserActivity\Renderers;

use MyBB\Core\UserActivity\Database\Models\UserActivity;

class PostRenderer extends AbstractRenderer
{
    const ACTIVITY_NAME = 'MyBB\Core\Database\Models\Post';

    /**
     * Render a given activity entry into a readable string.
     *
     * @param UserActivity $activity The activity to render.
     *
     * @return string The activity string. This string is not escaped on output, so should be properly cleaned before
     *                return.
     */
    public function render(UserActivity $activity)
    {
        return trans(
            'user_activity.activity_post',
            [
                'topic' => $activity->getExtraDetails()['topic_id']
            ]
        ); // TODO: Generate topic link...
    }

    /**
     * Get the full activity type name.
     *
     * EG: "MyBB\Core\Database\Models\Post".
     *
     * @return string
     */
    public function getActivityTypeName()
    {
        return static::ACTIVITY_NAME;
    }
}

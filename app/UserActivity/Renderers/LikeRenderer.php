<?php
/**
 * Like activity renderer.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2014, MyBB Group
 * @license   http://www.mybb.com/about/license GNU LESSER GENERAL PUBLIC LICENSE
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\UserActivity\Renderers;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Translation\Translator;
use MyBB\Core\Likes\Contracts\LikeableInterface;
use MyBB\Core\Likes\Database\Models\Like;
use MyBB\Core\UserActivity\Database\Models\UserActivity;

class LikeRenderer extends AbstractRenderer
{
    const ACTIVITY_NAME = 'MyBB\Core\Likes\Database\Models\Like';
    /**
     * @var UrlGenerator $urlGenerator
     */
    private $urlGenerator;

    /**
     * @param Translator   $lang
     * @param UrlGenerator $urlGenerator
     */
    public function __construct(Translator $lang, UrlGenerator $urlGenerator)
    {
        parent::__construct($lang);
        $this->urlGenerator = $urlGenerator;
    }

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
        $langName = 'user_activity.activity_like';
        $likedContentType = isset($activity->extra_details['liked_content_type']) ? $activity->extra_details['liked_content_type'] : '';

        if (!empty($likedContentType)) {
            $langName .= ".{$likedContentType}";
        }

        $contentLink = '#';

        if ($activity->activity_historable instanceof Like && $activity->activity_historable->likeable instanceof LikeableInterface) {
            $contentLink = $activity->activity_historable->likeable->getViewUrl();
        }

        return trans(
            $langName,
            [
                'content_title' => isset($activity->extra_details['liked_content_title']) ? $activity->extra_details['liked_content_title'] : '',
                'content_link'    => $contentLink,
                'user_link' => $this->urlGenerator->route('user.profile', [
                    'id' => isset($activity->extra_details['liked_content_user_id']) ? $activity->extra_details['liked_content_user_id'] : 0,
                    'slug' => isset($activity->extra_details['liked_content_user_name']) ? $activity->extra_details['liked_content_user_name'] : '',
                ]),
                'user_name' => isset($activity->extra_details['liked_content_user_name']) ? $activity->extra_details['liked_content_user_name'] : '',
            ]
        );
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

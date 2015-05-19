<?php
/**
 * Post activity renderer.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2015, MyBB Group
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\UserActivity\Renderers;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Translation\Translator;
use MyBB\Core\UserActivity\Database\Models\UserActivity;

class PostRenderer extends AbstractRenderer
{
	const ACTIVITY_NAME = 'MyBB\Core\Database\Models\Post';
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
		$topicTitle = '';
		if (isset($activity->extra_details['topic_title'])) {
			$topicTitle = $activity->extra_details['topic_title'];
		}

		$topicId = 0;
		if (isset($activity->extra_details['topic_id'])) {
			$topicId = (int) $activity->extra_details['topic_id'];
		}

		$topicSlug = '';
		if (isset($activity->extra_details['topic_slug'])) {
			$topicSlug = $activity->extra_details['topic_slug'];
		}

		return trans(
			'user_activity.activity_post',
			[
				'topic_title' => $topicTitle,
				'topic_id'    => $topicId,
				'topic_link'  => $this->urlGenerator->route(
					'topics.showPost',
					[
						'slug'   => $topicSlug,
						'id'     => $activity->activity_historable->topic_id,
						'postId' => $activity->activity_id,
					]
				),
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

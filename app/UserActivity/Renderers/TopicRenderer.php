<?php
/**
 * Topic activity renderer.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2015, MyBB Group
 * @license   http://www.mybb.com/about/license GNU LESSER GENERAL PUBLIC LICENSE
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\UserActivity\Renderers;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Translation\Translator;
use MyBB\Core\UserActivity\Database\Models\UserActivity;

class TopicRenderer extends AbstractRenderer
{
	const ACTIVITY_NAME = 'MyBB\Core\Database\Models\Topic';
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
		return trans(
			'user_activity.activity_topic',
			[
				'topic_title' => $activity->activity_historable->title,
				'topic_id'    => $activity->activity_id,
				'topic_link'  => $this->urlGenerator->route(
					'topics.show',
					[
						'slug'   => $activity->activity_historable->slug,
						'id'     => $activity->activity_id,
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

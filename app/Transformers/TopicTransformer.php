<?php

namespace MyBB\Core\Transformers;

use League\Fractal\TransformerAbstract;
use MyBB\Core\Database\Models\Topic;

class TopicTransformer extends TransformerAbstract
{
	public function transform(Topic $topic)
	{
		return [
			'title' => $topic->title,
			'slug' => $topic->slug,
			'forum_id' => (int) $topic->forum_id,
			'user_id' => (int) $topic->user_id,
			'first_post_id' => (int) $topic->first_post_id,
			'last_post_id' => (int) $topic->last_post_id,
			'views' => (int) $topic->views,
			'created_at' => (string) $topic->created_at,
			'updated_at' => (string) $topic->updated_at,
			'num_posts' => (int) $topic->num_posts,
		];
	}
}

<?php

use Illuminate\Database\Seeder;

class TopicsTableSeeder extends Seeder
{

	public function run()
	{
		DB::table('topics')->delete();
		$topic = [
			'title' => 'My Topic',
			'slug' => 'my-topic',
			'forum_id' => DB::table('forums')->where('slug', 'my-forum')->pluck('id'),
			'user_id' => DB::table('users')->where('name', 'Admin')->pluck('id'),
			'username' => 'Admin',
			'first_post_id' => null,
			'last_post_id' => null,
			'views' => 0,
			'created_at' => new \DateTime(),
			'updated_at' => new \DateTime(),
			'num_posts' => 1,
		];

		DB::table('topics')->insert($topic);
		DB::table('users')->where('name', 'Admin')->increment('num_topics');
		DB::table('forums')->where('slug', 'my-forum')->increment('num_topics');
	}

}

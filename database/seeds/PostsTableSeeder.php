<?php

use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{

	public function run()
	{
		DB::table('posts')->delete();
		$post = [
			'user_id'        => DB::table('users')->where('name', 'Admin')->pluck('id'),
			'topic_id'       => DB::table('topics')->where('slug', 'my-topic')->pluck('id'),
			'content'        => 'Hello MyBB 2.0!',
			'content_parsed' => 'Hello MyBB 2.0!',
			'created_at'     => new DateTime,
			'updated_at'     => new DateTime
		];

		$id = DB::table('posts')->insertGetId($post);
		DB::table('topics')->where('slug', 'my-topic')->increment('num_posts');
		DB::table('topics')->where('slug', 'my-topic')->update(
			[
				'last_post_id'  => $id,
				'first_post_id' => $id,
			]
		);

		DB::table('forums')->where('slug', 'my-forum')->increment('num_posts');
		DB::table('forums')->where('slug', 'my-forum')->update(
			[
				'last_post_id'      => $id,
				'last_post_user_id' => DB::table('users')->where('name', 'Admin')->pluck('id')
			]
		);

		DB::table('users')->where('name', 'Admin')->increment('num_posts');
	}

}

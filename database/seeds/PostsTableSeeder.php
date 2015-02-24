<?php

use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('posts')->delete();
        $post = [
				'user_id' => DB::table('users')->where('name', 'Admin')->pluck('id'),
				'topic_id' =>  DB::table('topics')->where('slug', 'my-topic')->pluck('id'),
				'content' => 'Hello MyBB 2.0!',
				'content_parsed' => 'Hello MyBB 2.0!',
				'created_at' => new DateTime,
				'updated_at' => new DateTime
		];

		$id = DB::table('posts')->insertGetId($post);
		DB::table('topics')->where('slug', 'my-topic')->update([
			'last_post_id' => $id,
			'first_post_id' => $id,
			'num_posts' => 1
		]);
    }

}
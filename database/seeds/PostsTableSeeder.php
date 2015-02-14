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

		DB::table('posts')->insert($post);
    }

}
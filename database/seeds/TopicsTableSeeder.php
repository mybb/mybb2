<?php

use Illuminate\Database\Seeder;

class TopicsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('topics')->delete();
        $topic = [
				'title' => 'My Topic',
				'slug' => 'my-topic',
				'forum_id' => DB::table('forums')->where('slug', 'my-forum')->pluck('id'),
				'user_id' => DB::table('users')->where('name', 'Admin')->pluck('id'),
				'first_post_id' => NULL,
				'last_post_id' => NULL,
				'views' => 0
		];

		DB::table('topics')->insert($topic);
    }

}
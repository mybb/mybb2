<?php

use Illuminate\Database\Seeder;

class ForumsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('forums')->delete();
        $forums = [
            [
				'title' => 'My Category',
				'slug' => 'my-category',
				'description' => '',
				'is_link' => 0,
				'link' => NULL,
				'num_topics' => 0,
				'num_posts' => 0,
				'last_post_id' => NULL,
				'last_post_user_id' => NULL,
				'left_id' => 2,
				'right_id' => 2,
				'parent_id' => NULL
            ],
            [
				'title' => 'My Forum',
				'slug' => 'my-forum',
				'description' => 'This is a test forum',
				'is_link' => 0,
				'link' => NULL,
				'num_topics' => 0,
				'num_posts' => 0,
				'last_post_id' => NULL,
				'last_post_user_id' => NULL,
				'left_id' => 0,
				'right_id' => 1,
				'parent_id' => 1
            ]
        ];

       DB::table('forums')->insert($forums);
    }

}
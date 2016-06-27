<?php
/**
 * @author  MyBB Group
 * @version 2.0.0
 * @package mybb/core
 * @license http://www.mybb.com/licenses/bsd3 BSD-3
 */

use Illuminate\Database\Seeder;

class ContentClassTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('content_class')->delete();

        $classes = [
            [
                'content' => 'user',
                'class'   => 'MyBB\\Core\\Database\\Models\\User',
            ],
            [
                'content' => 'forum',
                'class'   => 'MyBB\\Core\\Database\\Models\\Forum',
            ],
        ];

        DB::table('content_class')->insert($classes);
    }
}

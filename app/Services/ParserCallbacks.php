<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Services;

use MyBB\Core\Database\Models\Post;

class ParserCallbacks
{
    /**
     * @param int $pid
     *
     * @return string
     */
    public static function getPostLink($pid)
    {
        $post = Post::find($pid);

        return route('topics.showPost', [$post->topic->slug, $post->topic->id, $post->id]);
    }
}

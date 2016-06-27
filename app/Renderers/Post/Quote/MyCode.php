<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Renderers\Post\Quote;

use Illuminate\Foundation\Application;
use MyBB\Core\Database\Models\Post;

class MyCode implements QuoteInterface
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param Post $post
     *
     * @return string
     */
    public function renderFromPost(Post $post)
    {
        $post = $this->app->make('MyBB\Core\Presenters\Post', [$post]);
        $message = $post->content;
        $slapUsername = $post->author->name;
        $message = preg_replace(
            '#(>|^|\r|\n)/me ([^\r\n<]*)#i',
            "\\1* {$slapUsername} \\2",
            $message
        );
        $slap = trans('parser::parser.slap');
        $withTrout = trans('parser::parser.withTrout');
        $message = preg_replace(
            '#(>|^|\r|\n)/slap ([^\r\n<]*)#i',
            "\\1* {$slapUsername} {$slap} \\2 {$withTrout}",
            $message
        );
        $message = preg_replace("#\[attachment=([0-9]+?)\]#i", '', $message);

        return "[quote='" . e($post->author->name) . "' pid='{$post->id}' dateline='" .
        $post->created_at->getTimestamp() . "']\n{$message}\n[/quote]\n\n";
    }
}

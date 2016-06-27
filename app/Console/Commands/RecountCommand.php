<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use MyBB\Core\Database\Models\Forum;
use MyBB\Core\Database\Models\Post;
use MyBB\Core\Database\Models\Topic;
use MyBB\Core\Database\Models\User;
use Symfony\Component\Console\Input\InputOption;

class RecountCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'mybb:recount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recounts some of MyBB\'s counters. If called without options all recounts are run';

    /**
     * @var CacheRepository
     */
    private $cache;

    /**
     * @param CacheRepository $cache
     */
    public function __construct(CacheRepository $cache)
    {
        parent::__construct();

        $this->cache = $cache;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $options = $this->option();

        $runAll = !$options['forums'] && !$options['topics'] && !$options['users'];

        // If we want to recount topics and forums we should recount topics first
        // otherwise the forum counter will go crazy
        if ($runAll || $options['topics']) {
            $this->recountTopics();
        }

        if ($runAll || $options['forums']) {
            $this->recountForums();
        }

        if ($runAll || $options['users']) {
            $this->recountUsers();
        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['forums', 'f', InputOption::VALUE_NONE, 'Recount forum counters', null],
            ['topics', 't', InputOption::VALUE_NONE, 'Recount topic counters', null],
            ['users', 'u', InputOption::VALUE_NONE, 'Recount user counters', null],
        ];
    }

    private function recountForums()
    {
        $this->info('Recounting forum counters...');

        // We're calling the model directly to avoid caching issues
        $forums = Forum::all();
        foreach ($forums as $forum) {
            // We need the topics later to calculate the post number and the last post
            $topics = Topic::where('forum_id', '=', $forum->id)->orderBy('created_at', 'desc');
            $forum->num_topics = $topics->count();
            $numPosts = 0;
            $lastPost = null;
            $lastPostUser = null;

            foreach ($topics->get() as $topic) {
                $numPosts += $topic->num_posts;
                // We can simply override this variable all the time.
                // The topics are sorted so the last time we override this we have our last post
                $lastPost = $topic->last_post_id;
                $lastPostUser = $topic->lastPost->user_id;
            }

            $forum->num_posts = $numPosts;
            $forum->last_post_id = $lastPost;
            $forum->last_post_user_id = $lastPostUser;

            $forum->save();
        }

        // Override our old cache to populate our new numbers
        $this->cache->forever('forums.all', $forums);
        // We could also recache this cache but the recount tool is already busy
        // so probably better to leave it to the first user
        $this->cache->forget('forums.index_tree');

        $this->info('Done' . PHP_EOL);
    }

    private function recountTopics()
    {
        $this->info('Recounting topic counters...');

        $topics = Topic::all();
        foreach ($topics as $topic) {
            $posts = Post::where('topic_id', '=', $topic->id)->orderBy('created_at', 'desc');
            $topic->num_posts = $posts->count();
            // We could also update the first_post_id easily with the above query
            // but if that column is wrong everything is wrong
            $topic->last_post_id = $posts->first()->id;

            $topic->save();
        }

        $this->info('Done' . PHP_EOL);
    }

    private function recountUsers()
    {
        $this->info('Recounting user counters...');

        $users = User::all();
        foreach ($users as $user) {
            $user->num_posts = Post::where('user_id', '=', $user->id)->count();
            $user->num_topics = Topic::where('user_id', '=', $user->id)->count();

            $user->save();
        }

        $this->info('Done' . PHP_EOL);
    }
}

<?php
/**
 * Thread presenter class.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Presenters;

use Illuminate\Foundation\Application;
use McCool\LaravelAutoPresenter\BasePresenter;
use MyBB\Core\Database\Models\Post as PostModel;
use MyBB\Core\Database\Models\Topic as TopicModel;
use MyBB\Core\Database\Models\User as UserModel;
use MyBB\Core\Moderation\ModerationRegistry;

class TopicPresenter extends BasePresenter
{
    /** @var TopicModel $wrappedObject */

    /**
     * @var Application
     */
    private $app;

    /**
     * @var ModerationRegistry
     */
    protected $moderations;

    /**
     * @param TopicModel $resource The thread being wrapped by this presenter.
     * @param ModerationRegistry $moderations
     * @param Application $app
     */
    public function __construct(TopicModel $resource, ModerationRegistry $moderations, Application $app)
    {
        parent::__construct($resource);

        $this->moderations = $moderations;
        $this->app = $app;
    }

    /**
     * @return int
     */
    public function replies() : int
    {
        return $this->wrappedObject->num_posts - 1;
    }

    /**
     * @return User
     */
    public function author()
    {
        if ($this->wrappedObject->user_id == null) {
            $user = new UserModel();
            $user->id = 0;
            if ($this->wrappedObject->username != null) {
                $user->name = $this->wrappedObject->username;
            } else {
                $user->name = trans('general.guest');
            }

            $decoratedUser = $this->app->make('MyBB\Core\Presenters\UserPresenter', [$user]);

            return $decoratedUser;
        }

        return $this->wrappedObject->author;
    }

    /**
     * @return \MyBB\Core\Moderation\ModerationInterface[]
     */
    public function moderations()
    {
        $moderations = $this->moderations->getForContent(new PostModel());
        $decorated = [];
        $presenter = $this->app->make('autopresenter');

        foreach ($moderations as $moderation) {
            $decorated[] = $presenter->decorate($moderation);
        }

        return $decorated;
    }

    /**
     * @return Post
     */
    public function lastPost()
    {
        return $this->getWrappedObject()->lastPost;
    }

    /**
     * @return Forum
     */
    public function forum()
    {
        return $this->getWrappedObject()->forum;
    }
}

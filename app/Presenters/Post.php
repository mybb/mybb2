<?php
/**
 * Post presenter class.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Presenters;

use McCool\LaravelAutoPresenter\BasePresenter;
use MyBB\Auth\Contracts\Guard;
use MyBB\Core\Database\Models\Post as PostModel;
use MyBB\Core\Database\Models\User as UserModel;
use MyBB\Core\Likes\Database\Models\Like;

class Post extends BasePresenter
{
	/** @var PostModel $wrappedObject */

    /**
     * @var Guard $guard
     */
    private $guard;

    /**
     * @param PostModel $resource The post being wrapped by this presenter.
     * @param Guard     $guard
     */
	public function __construct(PostModel $resource, Guard $guard)
	{
		$this->wrappedObject = $resource;
        $this->guard = $guard;
	}

	public function author()
	{
		if($this->wrappedObject->user_id == null)
		{
			$user = new UserModel();
			if($this->wrappedObject->username != null)
			{
				$user->name = $this->wrappedObject->username;
			} else
			{
				$user->name = trans('general.guest');
			}

			$decoratedUser = app()->make('MyBB\Core\Presenters\User', [$user]);

			return $decoratedUser;
		}

		return $this->wrappedObject->author;
	}

    /**
     * Check whether the current user has liked the post.
     *
     * @return bool Whether the post has been liked by the current user.
     */
    public function hasLikedPost()
    {
        if ($this->guard->check()) {
            $user = $this->guard->user();

            $containsLike = $this->wrappedObject->likes->contains(function($key, Like $like) use (&$likes, &$numLikesToList, $user) {
                if ($like->user->id === $user->getAuthIdentifier()) {
                    return true;
                }

                return false;
            });

            return ($containsLike !== false);
        }

        return false;
    }
}

<?php
/**
 * Repository implementation for managing "liked" content, using the Eloquent ORM.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2014, MyBB Group
 * @license   http://www.mybb.com/about/license GNU LESSER GENERAL PUBLIC LICENSE
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\Likes\Database\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use MyBB\Auth\Contracts\Guard;
use MyBB\Core\Database\Models\User;
use MyBB\Core\Likes\Database\Models\Like As LikeModel;
use MyBB\Core\Likes\Database\Repositories\LikesRepositoryInterface;
use MyBB\Core\Likes\Traits\LikeableTrait;

class LikesRepository implements LikesRepositoryInterface
{
    /**
     * @var LikeModel $likesModel
     */
    protected $likesModel;
    /**
     * @var Guard $guard
     */
    protected $guard;

    /**
     * @param LikeModel $likesModel
     * @param Guard     $guard
     */
    public function __construct(LikeModel $likesModel, Guard $guard)
    {
        $this->likesModel = $likesModel;
        $this->guard = $guard;
    }

    /**
     * Get all of the likes created by a user, paginated.
     *
     * @param int|\MyBB\Core\Database\Models\User $user    The user to retrieve the likes for.
     * @param int                                 $perPage The number of likes per page.
     *
     * @return mixed
     */
    public function getAllLikesByUserPaginated($user, $perPage = 20)
    {
        return $this->likesModel->where('user_id', '=', $this->getUserIdForUser($user))->paginate($perPage);
    }

    /**
     * Retrieve all of the likes a piece of content has received.
     *
     * @param \Illuminate\Database\Eloquent\Model|LikeableTrait $content The content to retrieve the likes for.
     *
     * @param int                                               $perPage The number of likes to show per page.
     *
     * @return mixed
     */
    public function getAllLikesForContentPaginated(Model $content, $perPage = 10)
    {
        return $this->likesModel->where('content_id', '=', $content->id)->where('content_type', '=', get_class($content))->paginate($perPage);
    }

    /**
     * Toggle a like on or off for a given piece of content for the current user.
     *
     * @param \Illuminate\Database\Eloquent\Model|LikeableTrait $content The content to toggle the like for.
     *
     * @return null|LikeModel Null if a like was removed,a  like model instance if one was created.
     */
    public function toggleLikeForContent(Model $content)
    {
        if (($user = $this->guard->user()) !== null) {
            $existingLike = $this->likesModel->where('user_id', '=', $user->getAuthIdentifier())
                ->where('content_id', '=', $content->id)
                ->where('content_type', '=', get_class($content))->first();

            if ($existingLike !== null) {
                $existingLike->delete();

                $user->decrement('num_likes_made');
                $content->decrementNumLikes();
            } else {
                $newLike = $content->likes()->create(
                    [
                        'user_id' => $user->getAuthIdentifier(),
                    ]
                );

                $user->increment('num_likes_made');
                $content->incrementNumLikes();

                return $newLike;
            }
        }

        return null;
    }

    /**
     * Get the user ID for a given user.
     *
     * @param int|User $user The user to retrieve the ID for.
     *
     * @return int The user's ID.
     */
    protected function getUserIdForUser($user)
    {
        if (is_object($user) && $user instanceof User) {
            $user = $user->getAuthIdentifier();
        }

        return (int) $user;
    }

    /**
     * Get all of the likes for a set of entries of a specific content type.
     *
     * @param Model $contentType The type of the content to get all of the likes for.
     * @param array $ids         An array of IDs of the entries to get the likes for.
     *
     * @return mixed
     */
    function getAllLikesForContents(Model $contentType, array $ids)
    {
        return $this->likesModel->where('content_type', '=', get_class($contentType))->whereIn('content_id', $ids);
    }
}

<?php
/**
 * Trait to be used by models that can be "liked".
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2015, MyBB Group
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\Likes\Traits;

/**
 * @property int num_likes
 */
trait LikeableTrait
{
    /**
     * The name of the column caching the number of likes this content has received.
     *
     * @var string
     */
    protected $numLikesColumn = 'num_likes';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function likes()
    {
        return $this->morphMany(
            'MyBB\Core\Likes\Database\Models\Like',
            'likeable',
            'content_type',
            'content_id'
        );
    }

    /**
     * Get the number of likes this content has received.
     *
     * @return int
     */
    public function getNumLikes() : int
    {
        return (int)$this->{$this->numLikesColumn};
    }

    /**
     * Increment the number of likes for this content by 1.
     */
    public function incrementNumLikes()
    {
        $this->increment($this->numLikesColumn);
    }

    /**
     * Decrement the number of likes for this content by 1.
     */
    public function decrementNumLikes()
    {
        $this->decrement($this->numLikesColumn);
    }
}

<?php
/**
 * Trait to be used by models that can be "liked".
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2014, MyBB Group
 * @license   http://www.mybb.com/about/license GNU LESSER GENERAL PUBLIC LICENSE
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\Likes\Traits;

use Illuminate\Database\Eloquent\Model;

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
    public function getNumLikes()
    {
        return (int) $this->{$this->numLikesColumn};
    }
}

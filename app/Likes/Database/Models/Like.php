<?php
/**
 * Like model.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2014, MyBB Group
 * @license   http://www.mybb.com/about/license GNU LESSER GENERAL PUBLIC LICENSE
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\Likes\Database\Models;

use Illuminate\Database\Eloquent\Model;
use MyBB\Core\Database\Models\User;
use MyBB\Core\UserActivity\Contracts\ActivityStoreableInterface;
use MyBB\Core\UserActivity\Traits\UserActivityTrait;

/**
 * @property int id
 * @property int user_id
 * @property string content_type
 * @property int content_id
 */
class Like extends Model implements ActivityStoreableInterface
{
    use UserActivityTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'likes';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['user', 'likeable'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('MyBB\Core\Database\Models\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function likeable()
    {
        return $this->morphTo(null, 'content_type', 'content_id');
    }

    /**
     * Get the ID of the model.
     *
     * @return int
     */
    public function getContentId()
    {
        return $this->id;
    }

    /**
     * Get extra details about a model.
     *
     * @return array The extra details to store.
     */
    public function getExtraDetails()
    {
        $this->load('likeable');

        /** @var User $contentAuthor */
        $contentAuthor = $this->likeable->getContentAuthor();

        return [
            'liked_content_id' => $this->content_id,
            'liked_content_title' => $this->likeable->getContentTitle(),
            'liked_content_user_id' => $contentAuthor->id,
            'liked_content_user_name' => $contentAuthor->name,
            'liked_content_type' => $this->likeable->getContentTypeShortName(),
        ];
    }

	/**
	 * Check whether this activity entry should be saved.
	 *
	 * @return bool
	 */
	public function checkStoreable()
	{
		return true;
	}
}

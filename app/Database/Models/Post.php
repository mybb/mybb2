<?php
/**
 * Post model class.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use McCool\LaravelAutoPresenter\HasPresenter;
use MyBB\Core\Likes\Contracts\LikeableInterface;
use MyBB\Core\UserActivity\Contracts\ActivityStoreableInterface;
use MyBB\Core\UserActivity\Traits\UserActivityTrait;
use MyBB\Core\Likes\Traits\LikeableTrait;

class Post extends Model implements HasPresenter, LikeableInterface, ActivityStoreableInterface
{
    use SoftDeletes;
    use UserActivityTrait;
    use LikeableTrait;

    // @codingStandardsIgnoreStart

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

	// @codingStandardsIgnoreEnd

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = array();

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = array();

    /**
     * The date attributes.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    /**
     * Get the presenter class.
     *
     * @return string
     */
    public function getPresenterClass()
    {
        return 'MyBB\Core\Presenters\Post';
    }

    /**
     * A post belongs to a thread.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function topic()
    {
        return $this->belongsTo('MyBB\\Core\\Database\\Models\\Topic')->withTrashed();
    }

    /**
     * A post is created by (and belongs to) a user/author.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo('MyBB\\Core\\Database\\Models\\User', 'user_id');
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
        return [
            'topic_id' => $this->topic_id,
            'topic_slug' => $this->topic->slug,
            'topic_title' => $this->topic->title,
            'content' => $this->content_parsed,
        ];
    }

    /**
     * Get the title of the content being liked.
     *
     * @return string
     */
    public function getContentTitle()
    {
        if (!isset($this->topic)) {
            $this->load(['topic', 'topic.author']);
        }

        return $this->topic->title;
    }

    /**
     * Get the author of the content being liked.
     *
     * @return \MyBB\Core\Database\Models\User
     */
    public function getContentAuthor()
    {
        return $this->author;
    }

    /**
     * Get the short name of the content being liked.
     *
     * For example: "post".
     *
     * @return string
     */
    public function getContentTypeShortName()
    {
        return "post";
    }

    /**
     * Get the URL to view this content.
     *
     * @return string
     */
    public function getViewUrl()
    {
        return route('topics.showPost', [
           'slug' => $this->topic->slug,
           'id' => $this->topic_id,
           'postId' => $this->id,
        ]);
    }
}

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
use MyBB\Core\UserActivity\Traits\UserActivityTrait;

class Post extends Model implements HasPresenter
{
    use SoftDeletes;
    use UserActivityTrait;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
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
     * Get extra details about a model.
     *
     * @param Post $model The model being created and stored as activity.
     *
     * @return array The extra details to store.
     */
    public static function getActivityDetails(Post $model)
    {
        return [
            'topic_id' => $model->topic_id,
            'topic_slug' => $model->topic->slug,
            'content' => $model->content_parsed,
        ];
    }
}

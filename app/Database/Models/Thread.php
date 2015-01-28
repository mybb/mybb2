<?php
/**
 * Thread model class.
 *
 * @version 1.0.0
 * @author MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Models;

use Illuminate\Database\Eloquent\Model;
use McCool\LaravelAutoPresenter\HasPresenter;

class Thread extends Model implements HasPresenter
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'threads';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = array();

    /**
     * Get the presenter class.
     *
     * @return string
     */
    public function getPresenterClass()
    {
        return \MyBB\Core\Presenters\Thread::class; // TODO: Are we using PHP 5.5 as minimum? If so, this is fine...
    }

    /**
     * A thread has many posts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany('MyBB\\Core\\Database\\Models\\Post');
    }

    /**
     * A thread has many contributors (authors of posts belonging to the thread).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function contributors()
    {
        return $this->hasManyThrough('MyBB\\Core\\Database\\Models\\User', 'MyBB\\Core\\Database\\Models\\Post');
    }

    /**
     * A thread is created by (and belongs to) a user/author.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo('MyBB\\Core\\Database\\Models\\User');
    }

    // TODO: Other relations? Are the below necessary? Will probably be quicker to store last post and first post ID than alternatives...

    /**
     * A thread has a single first post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function firstPost()
    {
        return $this->hasOne('MyBB\\Core\\Database\\Models\\Post');
    }

    /**
     * A thread has a single last post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function lastPost()
    {
        return $this->hasOne('MyBB\\Core\\Database\\Models\\Post');
    }
}

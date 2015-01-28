<?php
/**
 * Post model class.
 *
 * @version 1.0.0
 * @author MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Models;

use Illuminate\Database\Eloquent\Model;
use McCool\LaravelAutoPresenter\HasPresenter;

class Post extends Model implements HasPresenter
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'posts';

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
        return \MyBB\Core\Presenters\Post::class; // TODO: Are we using PHP 5.5 as minimum? If so, this is fine...
    }

    /**
     * A post belongs to a thread.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo('MyBB\\Core\\Database\\Models\\Thread');
    }

    /**
     * A post is created by (and belongs to) a user/author.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo('MyBB\\Core\\Database\\Models\\User');
    }
}

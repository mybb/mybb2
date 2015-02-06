<?php
/**
 * Forum model class.
 *
 * @version 1.0.0
 * @author MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Models;

use Kalnoy\Nestedset\Node;
use McCool\LaravelAutoPresenter\HasPresenter;

class Forum extends Node implements HasPresenter
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'forums';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = array();

    /**
     * Nested set column IDs.
     */
    const LFT = 'left_id';
    const RGT = 'right_id';
    const PARENT_ID = 'parent_id';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['left_id', 'right_id', 'parent_id'];

    /**
     * Get the presenter class.
     *
     * @return string
     */
    public function getPresenterClass()
    {
        return \MyBB\Core\Presenters\Forum::class; // TODO: Are we using PHP 5.5 as minimum? If so, this is fine...
    }

    /**
     * A forum contains many threads.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function threads()
    {
        return $this->hasMany('MyBB\\Core\\Database\\Models\\Thread');
    }

    /**
     * A forum contains many posts, through its threads.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function posts()
    {
        return $this->hasManyThrough('MyBB\\Core\\Database\\Models\\Post', 'MyBB\\Core\\Database\\Models\\Thread');
    }
}

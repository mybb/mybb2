<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Models;

use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
    // @codingStandardsIgnoreStart

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var boolean
     */
    public $incrementing = false;
    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;

    // @codingStandardsIgnoreEnd

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token',
        'as_topics',
        'user_id',
        'topics',
        'posts',
        'keywords',
    ];
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['user_id'];
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'token';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'searchlog';
}

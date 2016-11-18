<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Models;

use Illuminate\Database\Eloquent\Model;

class WarningType extends Model
{
    /**
     * @var string
     */
    protected $table = 'warning_types';
    /**
     * @var bool
     */
    public $timestamps = false;

//    protected $fillable = [
//        'reason',
//        'points',
//        'expiration_multiple',
//        'expiration_type',
//        'must_acknowledge',
//    ];

    /**
     * @var array
     */
    protected $guarded = ['id'];
}
